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

    public $tries = 3;

    public $backoff = [30, 120, 300];

    protected $claimId;

    public function __construct($claimId)
    {
        $this->claimId = $claimId;
    }

    public function handle(NotionService $notion)
    {
        try {
            if (!$notion->isEnabled()) {
                return;
            }

            $claim = Claim::find($this->claimId);

            if (!$claim) {
                Log::info('Notion claim push skipped - claim not found', ['claim_id' => $this->claimId]);
                return;
            }

            if ($claim->notion_page_id) {
                return;
            }

            // Registration normally queues the client first, but make this
            // job safe when workers run concurrently or the earlier job failed.
            if ($claim->user && !$claim->user->notion_page_id) {
                $clientPageId = $notion->createClientPage($claim->user);
                if (!$clientPageId) {
                    throw new \RuntimeException('Notion did not return a client page id.');
                }
                $claim->user->notion_page_id = $clientPageId;
                $claim->user->saveQuietly();
            }

            $pageId = $notion->createClaimPage($claim->fresh(['user']));

            if ($pageId) {
                $claim->notion_page_id = $pageId;
                $claim->saveQuietly();

                Log::info('Claim pushed to Notion', ['claim_id' => $claim->id, 'notion_page_id' => $pageId]);
                return;
            }

            throw new \RuntimeException('Notion did not return a claim page id.');
        } catch (\Exception $e) {
            Log::error('Failed to push claim to Notion: ' . $e->getMessage(), ['claim_id' => $this->claimId]);
            throw $e;
        }
    }
}
