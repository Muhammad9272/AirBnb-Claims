<?php

namespace App\Jobs;

use App\Models\Claim;
use App\Services\NotionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Pushes a website-originated status/amount/comment change to the claim's
 * existing Notion page. Only dispatched for changes that did NOT originate
 * from Notion itself (see ClaimStatusService) - otherwise a Notion edit
 * would round-trip straight back to Notion.
 */
class PushClaimUpdateToNotion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $backoff = [30, 120, 300];

    protected $claimId;
    protected $fields;

    public function __construct($claimId, array $fields)
    {
        $this->claimId = $claimId;
        $this->fields = $fields;
    }

    public function handle(NotionService $notion)
    {
        try {
            if (!$notion->isEnabled()) {
                return;
            }

            $claim = Claim::find($this->claimId);

            if (!$claim) {
                Log::info('Notion claim update push skipped - claim not found', ['claim_id' => $this->claimId]);
                return;
            }

            if (!$claim->notion_page_id) {
                PushClaimToNotion::dispatch($claim->id);
                Log::warning('Notion claim update converted to a create push because the page link was missing', ['claim_id' => $claim->id]);
                return;
            }

            $ok = $notion->updateClaimPage($claim->notion_page_id, $this->fields);

            if ($ok) {
                Log::info('Claim update pushed to Notion', ['claim_id' => $claim->id, 'fields' => array_keys($this->fields)]);
                return;
            }

            throw new \RuntimeException('Notion claim page update failed.');
        } catch (\Exception $e) {
            Log::error('Failed to push claim update to Notion: ' . $e->getMessage(), ['claim_id' => $this->claimId]);
            throw $e;
        }
    }
}
