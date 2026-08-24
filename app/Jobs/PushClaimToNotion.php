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

class PushClaimToNotion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $claimId;

    public function __construct($claimId)
    {
        $this->claimId = $claimId;
    }

    public function handle()
    {
        try {
            $claim = Claim::find($this->claimId);

            if (!$claim) {
                Log::info('Notion claim push skipped - claim not found', ['claim_id' => $this->claimId]);
                return;
            }

            $pageId = (new NotionService())->createClaimPage($claim);

            if ($pageId) {
                $claim->notion_page_id = $pageId;
                $claim->saveQuietly();

                Log::info('Claim pushed to Notion', ['claim_id' => $claim->id, 'notion_page_id' => $pageId]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to push claim to Notion: ' . $e->getMessage(), ['claim_id' => $this->claimId]);
        }
    }
}
