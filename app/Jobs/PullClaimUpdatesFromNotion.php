<?php

namespace App\Jobs;

use App\Models\Claim;
use App\Services\ClaimStatusService;
use App\Services\NotionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Polls Notion for claims the ops team edited and applies status / amount /
 * requested-info changes back to the website via ClaimStatusService, so a
 * Notion-originated change gets the same commission-charge + email side
 * effects as one made from the admin panel.
 *
 * Only claims created after the Notion push feature shipped have a
 * notion_page_id and are eligible to sync back - there's no historical
 * backfill.
 */
class PullClaimUpdatesFromNotion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const CACHE_KEY = 'notion_claims_last_synced_at';
    // Overlap window so a slow/missed run never skips a page; re-applying an
    // unchanged value is a no-op (see the comparison below).
    private const LOOKBACK_MINUTES = 15;

    public function handle()
    {
        $notion = new NotionService();
        $statusService = new ClaimStatusService();

        $since = Cache::get(self::CACHE_KEY, now()->subMinutes(self::LOOKBACK_MINUTES)->toIso8601String());
        $runStartedAt = now()->toIso8601String();

        $pages = $notion->queryUpdatedClaimPages($since);

        foreach ($pages as $page) {
            try {
                $this->syncPage($page, $notion, $statusService);
            } catch (\Exception $e) {
                Log::error('Failed to sync claim page from Notion: ' . $e->getMessage(), [
                    'page_id' => $page['id'] ?? null,
                ]);
            }
        }

        Cache::forever(self::CACHE_KEY, $runStartedAt);
    }

    private function syncPage(array $page, NotionService $notion, ClaimStatusService $statusService): void
    {
        $fields = $notion->extractClaimFields($page);

        $claim = Claim::where('notion_page_id', $fields['page_id'])->first();
        if (!$claim) {
            // Not one of our claims (or not pushed yet) - nothing to sync back to.
            return;
        }

        $statusChanged = $fields['status'] !== null && $fields['status'] !== $claim->status;
        $amountChanged = $fields['amount_approved'] !== null
            && round((float) $fields['amount_approved'], 2) !== round((float) $claim->amount_approved, 2);

        if (!$statusChanged && !$amountChanged) {
            return;
        }

        $newStatus = $fields['status'] ?? $claim->status;

        // Only carry the "requested information" note through on an actual
        // status transition - otherwise the same lingering Notion text would
        // create a duplicate comment (and duplicate client email) on every
        // poll cycle.
        $comment = $statusChanged ? $fields['requested_information'] : null;

        $result = $statusService->applyStatusChange($claim, $newStatus, [
            'approved_amount' => $fields['amount_approved'] ?? $claim->amount_approved,
            'comment' => $comment,
            'changed_by_id' => null,
            'changed_by_label' => 'Notion Sync',
            'origin' => 'notion',
        ]);

        if (!$result['success']) {
            Log::error('Notion-originated status change failed', [
                'claim_id' => $claim->id,
                'error' => $result['error'],
            ]);
            return;
        }

        Log::info('Claim synced from Notion', [
            'claim_id' => $claim->id,
            'status' => $newStatus,
            'amount_approved' => $fields['amount_approved'],
        ]);
    }
}
