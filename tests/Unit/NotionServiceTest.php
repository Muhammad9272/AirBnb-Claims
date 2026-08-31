<?php

namespace Tests\Unit;

use App\Services\NotionService;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class NotionServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.notion.enabled', true);
        config()->set('services.notion.secret_key', 'test-notion-token');
        config()->set('services.notion.claims_database_id', 'claims-db');
        config()->set('services.notion.clients_database_id', 'clients-db');
        config()->set('services.notion.page_prefix', '[STAGING] ');
    }

    public function test_extracts_live_claim_property_shapes(): void
    {
        $fields = (new NotionService())->extractClaimFields([
            'id' => 'page-1',
            'last_edited_time' => '2026-08-31T10:00:00.000Z',
            'properties' => [
                'Status' => ['status' => ['name' => 'Won']],
                'Amount Awarded' => ['number' => 725.50],
                'Requested information' => [
                    'rich_text' => [
                        ['plain_text' => 'Please send '],
                        ['plain_text' => 'the invoice.'],
                    ],
                ],
            ],
        ]);

        $this->assertSame('page-1', $fields['page_id']);
        $this->assertSame('approved', $fields['status']);
        $this->assertSame(725.50, $fields['amount_approved']);
        $this->assertSame('Please send the invoice.', $fields['requested_information']);
    }

    public function test_paginates_updated_claim_queries(): void
    {
        Http::fakeSequence()
            ->push([
                'results' => [['id' => 'page-1']],
                'has_more' => true,
                'next_cursor' => 'cursor-2',
            ])
            ->push([
                'results' => [['id' => 'page-2']],
                'has_more' => false,
                'next_cursor' => null,
            ]);

        $pages = (new NotionService())->queryUpdatedClaimPages('2026-08-31T09:00:00Z');

        $this->assertSame(['page-1', 'page-2'], array_column($pages, 'id'));
        Http::assertSentCount(2);
        Http::assertSent(function ($request) {
            return isset($request['start_cursor'])
                && $request['start_cursor'] === 'cursor-2'
                && $request['page_size'] === 100;
        });
    }

    public function test_failed_claim_query_throws_so_the_queue_can_retry(): void
    {
        Http::fake([
            '*' => Http::response(['message' => 'temporary failure'], 503),
        ]);

        $this->expectException(RuntimeException::class);

        (new NotionService())->queryUpdatedClaimPages('2026-08-31T09:00:00Z');
    }

    public function test_splits_long_rich_text_into_notion_safe_chunks(): void
    {
        Http::fake([
            '*' => Http::response(['id' => 'page-1'], 200),
        ]);

        $comment = str_repeat('x', 4500);
        $this->assertTrue((new NotionService())->updateClaimPage('page-1', [
            'comment' => $comment,
        ]));

        Http::assertSent(function ($request) {
            $chunks = $request['properties']['Requested information']['rich_text'];

            return count($chunks) === 3
                && mb_strlen($chunks[0]['text']['content']) === 2000
                && mb_strlen($chunks[1]['text']['content']) === 2000
                && mb_strlen($chunks[2]['text']['content']) === 500;
        });
    }
}
