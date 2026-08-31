<?php

namespace App\Jobs;

use App\Models\Claim;
use App\Models\ClaimComment;
use App\Services\ClaimStatusService;
use App\Services\NotionService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
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

    public $tries = 3;

    public $backoff = [30, 120, 300];

    private const CACHE_KEY = 'notion_claims_last_synced_at';
    // Overlap window so a slow/missed run never skips a page; re-applying an
    // unchanged value is a no-op (see the comparison below).
    private const LOOKBACK_MINUTES = 15;

    public function middleware(): array
    {
        return [(new WithoutOverlapping('notion-claims-pull'))->releaseAfter(30)->expireAfter(300)];
    }

    public function handle(NotionService $notion, ClaimStatusService $statusService)
    {
        if (!$notion->isEnabled()) {
            return;
        }

        $lastSuccessfulSync = Cache::get(self::CACHE_KEY, now()->toIso8601String());
        $since = Carbon::parse($lastSuccessfulSync)
            ->subMinutes(self::LOOKBACK_MINUTES)
            ->toIso8601String();
        $runStartedAt = now()->toIso8601String();

        $pages = $notion->queryUpdatedClaimPages($since);

        foreach ($pages as $page) {
            $this->syncPage($page, $notion, $statusService);
        }

        // Only advance after every page completed. If the API or any page
        // fails, the queued job retries from the previous successful marker.
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
        $requestedInformation = trim((string) ($fields['requested_information'] ?? ''));
        $notionComment = $requestedInformation !== '' ? "[Synced from Notion] {$requestedInformation}" : null;
        $requestedInformationChanged = $notionComment !== null
            && !ClaimComment::where('claim_id', $claim->id)
                ->where('is_admin', true)
                ->where('comment', $notionComment)
                ->exists();

        if (!$statusChanged && !$amountChanged && !$requestedInformationChanged) {
            return;
        }

        $newStatus = $fields['status'] ?? $claim->status;

        $comment = $requestedInformationChanged ? $requestedInformation : null;

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
            throw new \RuntimeException($result['error']);
        }

        Log::info('Claim synced from Notion', [
            'claim_id' => $claim->id,
            'status' => $newStatus,
            'amount_approved' => $fields['amount_approved'],
        ]);
    }
}
