<?php

namespace Tests\Feature;

use App\Jobs\PullClaimUpdatesFromNotion;
use App\Models\Claim;
use App\Models\User;
use App\Services\ClaimStatusService;
use App\Services\NotionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class PullClaimUpdatesFromNotionTest extends TestCase
{
    use DatabaseTransactions;

    public function test_requested_information_syncs_without_a_status_change_and_is_idempotent(): void
    {
        Queue::fake();

        $user = User::create([
            'name' => 'Notion Pull User',
            'email' => 'notion-pull@example.test',
            'password' => Hash::make('Testing123'),
            'role_type' => 'user',
        ]);
        $claim = Claim::create([
            'user_id' => $user->id,
            'claim_number' => 'NOTION-PULL-1',
            'status' => 'pending',
        ]);
        $claim->notion_page_id = 'notion-page-1';
        $claim->saveQuietly();

        $page = ['id' => 'notion-page-1'];
        $fields = [
            'page_id' => 'notion-page-1',
            'status' => 'pending',
            'amount_approved' => null,
            'requested_information' => 'Please upload the final invoice.',
            'last_edited_time' => '2026-08-31T10:00:00.000Z',
        ];

        $notion = Mockery::mock(NotionService::class);
        $notion->shouldReceive('isEnabled')->twice()->andReturnTrue();
        $notion->shouldReceive('queryUpdatedClaimPages')->twice()->andReturn([$page]);
        $notion->shouldReceive('extractClaimFields')->twice()->with($page)->andReturn($fields);

        $job = new PullClaimUpdatesFromNotion();
        $service = new ClaimStatusService();
        $job->handle($notion, $service);
        $job->handle($notion, $service);

        $this->assertDatabaseCount('claim_comments', 1);
        $this->assertDatabaseHas('claim_comments', [
            'claim_id' => $claim->id,
            'comment' => '[Synced from Notion] Please upload the final invoice.',
            'is_admin' => 1,
        ]);
        $this->assertDatabaseMissing('claim_status_histories', [
            'claim_id' => $claim->id,
        ]);
    }

    public function test_failed_query_does_not_advance_the_success_checkpoint(): void
    {
        Cache::forever('notion_claims_last_synced_at', '2026-08-31T09:00:00+00:00');

        $notion = Mockery::mock(NotionService::class);
        $notion->shouldReceive('isEnabled')->once()->andReturnTrue();
        $notion->shouldReceive('queryUpdatedClaimPages')->once()->andThrow(new RuntimeException('temporary outage'));

        try {
            (new PullClaimUpdatesFromNotion())->handle($notion, new ClaimStatusService());
            $this->fail('Expected the Notion query to fail.');
        } catch (RuntimeException $e) {
            $this->assertSame('temporary outage', $e->getMessage());
        }

        $this->assertSame('2026-08-31T09:00:00+00:00', Cache::get('notion_claims_last_synced_at'));
    }
}
