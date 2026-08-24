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

    protected $claimId;
    protected $fields;

    public function __construct($claimId, array $fields)
    {
        $this->claimId = $claimId;
        $this->fields = $fields;
    }

    public function handle()
    {
        try {
            $claim = Claim::find($this->claimId);

            if (!$claim || !$claim->notion_page_id) {
                Log::info('Notion claim update push skipped - no linked Notion page', ['claim_id' => $this->claimId]);
                return;
            }

            $ok = (new NotionService())->updateClaimPage($claim->notion_page_id, $this->fields);

            if ($ok) {
                Log::info('Claim update pushed to Notion', ['claim_id' => $claim->id, 'fields' => array_keys($this->fields)]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to push claim update to Notion: ' . $e->getMessage(), ['claim_id' => $this->claimId]);
        }
    }
}
