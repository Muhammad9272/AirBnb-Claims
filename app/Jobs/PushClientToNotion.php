<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\NotionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PushClientToNotion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $backoff = [30, 120, 300];

    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function handle(NotionService $notion)
    {
        try {
            if (!$notion->isEnabled()) {
                return;
            }

            $user = User::find($this->userId);

            if (!$user) {
                Log::info('Notion client push skipped - user not found', ['user_id' => $this->userId]);
                return;
            }

            if ($user->notion_page_id) {
                return;
            }

            $pageId = $notion->createClientPage($user);

            if ($pageId) {
                $user->notion_page_id = $pageId;
                $user->saveQuietly();

                Log::info('Client pushed to Notion', ['user_id' => $user->id, 'notion_page_id' => $pageId]);
                return;
            }

            throw new \RuntimeException('Notion did not return a client page id.');
        } catch (\Exception $e) {
            Log::error('Failed to push client to Notion: ' . $e->getMessage(), ['user_id' => $this->userId]);
            throw $e;
        }
    }
}
