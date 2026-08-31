<?php

namespace App\Services;

use App\Classes\GeniusMailer;
use App\Models\Claim;
use App\Models\ClaimComment;
use App\Models\ClaimStatusHistory;
use App\Models\GeneralSetting;
use App\Models\InfluencerCommission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Stripe;

/**
 * Applies a claim status change end-to-end: commission charging, influencer
 * commission handling, status history, comment thread, and the client
 * notification email. Extracted from ClaimManagementController::updateStatus
 * so a Notion-originated status change gets exactly the same side effects as
 * one made from the admin panel - a Notion sync that only touched the
 * `status` column directly would silently skip commission charging and the
 * client email.
 *
 * $options:
 *   - approved_amount   (float, required for approved/partial_payout)
 *   - rejection_reason  (string, required for rejected)
 *   - comment           (string, optional - also carries Notion's
 *                         "requested information" note)
 *   - changed_by_id     (int|null, admin user id; null for Notion-originated changes)
 *   - changed_by_label  (string, human label used for commission notes when
 *                         there's no authenticated admin, e.g. "Notion Sync")
 */
class ClaimStatusService
{
    public function applyStatusChange(Claim $claim, string $newStatus, array $options = []): array
    {
        $oldStatus = $claim->status;
        $oldApprovedAmount = $claim->amount_approved;
        $changedById = $options['changed_by_id'] ?? null;
        $changedByLabel = $options['changed_by_label'] ?? ($changedById ? optional(User::find($changedById))->name : 'System');
        $comment = $options['comment'] ?? null;
        // 'website' (default) or 'notion' - controls whether this change gets
        // pushed back to Notion. A Notion-originated change must NOT be
        // pushed back, or it would round-trip straight back to Notion.
        $origin = $options['origin'] ?? 'website';

        DB::beginTransaction();

        try {
            $claim->status = $newStatus;

            if (in_array($newStatus, ['approved', 'partial_payout'])) {
                $claim->amount_approved = $options['approved_amount'] ?? $claim->amount_approved;

                $activeSubscription = $claim->user->activeuserSubscriptions()->first();
                if ($activeSubscription && $activeSubscription->plan) {
                    $commissionRate = $activeSubscription->plan->commission_percentage ?? 0;
                    $claim->commission_amount = ($claim->amount_approved * $commissionRate) / 100;

                    $isCommissionAlreadyPaid = $claim->is_commission_paid && $claim->payment_id;

                    if (!$isCommissionAlreadyPaid) {
                        $user = User::find($claim->user_id);
                        if ($user && isset($user->stripe_customer_id)) {
                            Stripe::setApiKey(config('services.stripe.secret'));

                            try {
                                $paymentIntent = PaymentIntent::create([
                                    'amount' => (int) ($claim->commission_amount * 100),
                                    'currency' => 'usd',
                                    'customer' => $user->stripe_customer_id,
                                    'payment_method' => $user->stripe_payment_method_id,
                                    'off_session' => true,
                                    'confirm' => true,
                                    'description' => floatval($activeSubscription->plan->commission_percentage) . '% commission for claim #' . $claim->id,
                                ]);

                                if ($paymentIntent->status === 'succeeded') {
                                    $claim->is_commission_paid = true;
                                    $claim->payment_id = $paymentIntent->id;

                                    Log::info('Commission charged successfully', [
                                        'claim_id' => $claim->id,
                                        'payment_intent' => $paymentIntent->id,
                                        'amount' => $claim->commission_amount,
                                    ]);
                                }
                            } catch (\Exception $paymentException) {
                                Log::error('Commission payment failed', [
                                    'claim_id' => $claim->id,
                                    'error' => $paymentException->getMessage(),
                                    'user_id' => $user->id,
                                ]);
                                $user->update(['stripe_payment_method_id' => null]);
                                DB::commit();
                                return ['success' => false, 'error' => 'User Payment card is invalid or has been declined.'];
                            }
                        }
                    } else {
                        Log::info('Commission already paid - skipping charge', [
                            'claim_id' => $claim->id,
                            'payment_id' => $claim->payment_id,
                            'amount' => $claim->commission_amount,
                        ]);
                    }
                }
            }

            if ($newStatus === 'rejected') {
                $claim->rejection_reason = $options['rejection_reason'] ?? $claim->rejection_reason;
                $claim->is_commission_paid = false;
                $claim->payment_id = null;
            }

            $claim->save();

            $statusChanged = $oldStatus !== $newStatus;
            $amountChanged = round((float) $oldApprovedAmount, 2) !== round((float) $claim->amount_approved, 2);

            if (($statusChanged || $amountChanged) && in_array($newStatus, ['approved', 'partial_payout', 'rejected'])) {
                $this->updateInfluencerCommission($claim, $newStatus, $changedByLabel);
            }

            if ($statusChanged) {
                ClaimStatusHistory::create([
                    'claim_id' => $claim->id,
                    'user_id' => $changedById,
                    'from_status' => $oldStatus,
                    'to_status' => $newStatus,
                    'notes' => $comment,
                ]);
            }

            $createdComment = null;
            if (!empty($comment)) {
                $createdComment = ClaimComment::create([
                    'claim_id' => $claim->id,
                    // claim_comments.user_id is NOT NULL with no real FK; attribute
                    // system/Notion-originated comments to the primary admin (id 1,
                    // same convention already used in app/Http/Middleware/Permissions.php)
                    // and prefix the text so authorship in the UI stays unambiguous.
                    'user_id' => $changedById ?? 1,
                    'comment' => $changedById ? $comment : "[Synced from Notion] {$comment}",
                    'is_admin' => true,
                ]);
            }

            if ($statusChanged) {
                NotificationService::claimStatusChanged($claim, $oldStatus, $newStatus, $comment);

                try {
                    (new GeniusMailer())->sendClaimStatusUpdateEmail($claim, $newStatus, $comment);
                } catch (\Exception $e) {
                    Log::error('Failed to send claim status update email', [
                        'claim_id' => $claim->id,
                        'user_id' => $claim->user_id,
                        'error' => $e->getMessage(),
                    ]);
                }
            } elseif ($createdComment) {
                NotificationService::newComment($createdComment);
            }

            DB::commit();

            if ($origin === 'website' && ($statusChanged || $amountChanged || !empty($comment))) {
                \App\Jobs\PushClaimUpdateToNotion::dispatch($claim->id, [
                    'status' => $newStatus,
                    'amount_approved' => $claim->amount_approved,
                    'comment' => $comment,
                ]);
            }

            return ['success' => true, 'error' => null];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update claim status', [
                'claim_id' => $claim->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return ['success' => false, 'error' => 'Failed to update claim status. ' . $e->getMessage()];
        }
    }

    private function updateInfluencerCommission(Claim $claim, string $newStatus, string $changedByLabel): void
    {
        try {
            $commission = InfluencerCommission::where('claim_id', $claim->id)->first();

            if (!$commission) {
                return;
            }

            if ($newStatus === 'approved' || $newStatus === 'partial_payout') {
                $gs = GeneralSetting::first();

                if (!$gs || !$gs->influencer_commission_percentage) {
                    $commission->update([
                        'status' => 'rejected',
                        'notes' => ($commission->notes ?? '') . "\n[" . now()->format('Y-m-d H:i:s') . '] Commission rejected: Influencer commission is disabled in settings.',
                    ]);
                    return;
                }

                $referrer = User::find($commission->influencer_user_id);
                if (!$referrer || $referrer->role_type !== 'influencer') {
                    $commission->update([
                        'status' => 'rejected',
                        'commission_amount' => 0,
                        'notes' => ($commission->notes ?? '') . "\n[" . now()->format('Y-m-d H:i:s') . '] Commission rejected: Referrer is no longer an influencer or account not found.',
                    ]);

                    Log::info('Commission rejected - referrer not an influencer', [
                        'commission_id' => $commission->id,
                        'claim_id' => $claim->id,
                        'referrer_id' => $commission->influencer_user_id,
                    ]);
                    return;
                }

                if ($gs->influencer_commission_duration_days) {
                    $claimCreationDate = $claim->created_at;
                    $commissionEndDate = $claimCreationDate->copy()->addDays($gs->influencer_commission_duration_days);
                    $now = now();

                    if ($now->greaterThan($commissionEndDate)) {
                        $commission->update([
                            'status' => 'rejected',
                            'commission_amount' => 0,
                            'notes' => ($commission->notes ?? '') . "\n[" . now()->format('Y-m-d H:i:s') . "] Commission rejected: Claim approved after commission period expired. Claim created on {$claimCreationDate->format('Y-m-d')}, commission period ({$gs->influencer_commission_duration_days} days) ended on {$commissionEndDate->format('Y-m-d')}.",
                        ]);

                        Log::info('Influencer commission rejected - period expired at approval', [
                            'commission_id' => $commission->id,
                            'claim_id' => $claim->id,
                            'claim_created' => $claimCreationDate,
                            'claim_approved' => $now,
                            'commission_end_date' => $commissionEndDate,
                        ]);
                        return;
                    }
                }

                $finalCommission = round(($claim->amount_approved * $gs->influencer_commission_percentage) / 100, 2);

                $approvalNotes = ($commission->notes ?? '') . "\n[" . now()->format('Y-m-d H:i:s') . '] Commission approved. ';
                $approvalNotes .= 'Final commission calculated based on approved amount: $' . number_format($claim->amount_approved, 2) . '. ';
                $approvalNotes .= "Commission rate: {$gs->influencer_commission_percentage}%. ";
                $approvalNotes .= 'Final commission amount: $' . number_format($finalCommission, 2) . '. ';
                $approvalNotes .= "Approved by: {$changedByLabel}.";

                $commission->update([
                    'commission_amount' => $finalCommission,
                    'status' => 'approved',
                    'commission_date' => now(),
                    'notes' => $approvalNotes,
                ]);

                Log::info('Influencer commission approved', [
                    'commission_id' => $commission->id,
                    'claim_id' => $claim->id,
                    'final_commission' => $finalCommission,
                ]);
            } elseif ($newStatus === 'rejected') {
                $rejectionNotes = ($commission->notes ?? '') . "\n[" . now()->format('Y-m-d H:i:s') . '] Commission rejected: Claim was rejected by admin. ';
                $rejectionNotes .= "Rejected by: {$changedByLabel}.";

                $commission->update([
                    'status' => 'rejected',
                    'commission_amount' => 0,
                    'notes' => $rejectionNotes,
                ]);

                Log::info('Influencer commission rejected due to claim rejection', [
                    'commission_id' => $commission->id,
                    'claim_id' => $claim->id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update influencer commission: ' . $e->getMessage(), [
                'claim_id' => $claim->id,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
