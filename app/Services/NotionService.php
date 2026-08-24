<?php

namespace App\Services;

use App\Models\Claim;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin wrapper around the Notion API, following the same Http:: facade +
 * config/services.php pattern already used for Stripe/AliPay in this app.
 *
 * Property names/types below were pulled directly from the live Claims
 * (2de3a01e-c221-82b3-b8e1-01ac7e991bbd) and Clients
 * (8993a01e-c221-8211-8707-01158cd66348) databases via the Notion API on
 * 2026-08-14 - not guesses. Some property names have trailing spaces
 * ("claim Number ") - that's real, not a typo, and must match exactly.
 */
class NotionService
{
    private const API_BASE = 'https://api.notion.com/v1';
    private const API_VERSION = '2022-06-28';

    /**
     * Website status enum => Notion "Status" option name. Status is a
     * `status`-type property in Notion (not `select`) - different JSON shape.
     * Notion's "Queue" and "Pending" both exist there; "Queue" has no website
     * equivalent and is intentionally left unmapped (a Notion-side pre-intake
     * stage that never gets pulled back).
     */
    private const STATUS_MAP = [
        'pending' => 'Pending',
        'under_review' => 'In Review',
        'additional_info_requested' => 'Need More Info',
        'challenge_1' => 'Challenge 1',
        'challenge_2' => 'Challenge 2',
        'approved' => 'Won',
        'partial_payout' => 'Partial',
        'rejected' => 'Denied',
    ];

    public function claimsDatabaseId(): ?string
    {
        return config('services.notion.claims_database_id');
    }

    public function clientsDatabaseId(): ?string
    {
        return config('services.notion.clients_database_id');
    }

    /**
     * Create the Notion page for a newly-created claim. Returns the Notion
     * page id on success, or null on failure (never throws - callers should
     * not let a Notion outage block claim submission).
     */
    public function createClaimPage(Claim $claim): ?string
    {
        $databaseId = $this->claimsDatabaseId();
        if (!$databaseId) {
            Log::warning('Notion claims database id not configured, skipping push', ['claim_id' => $claim->id]);
            return null;
        }

        $response = $this->request('POST', '/pages', [
            'parent' => ['database_id' => $databaseId],
            'properties' => $this->claimProperties($claim),
        ]);

        if (!$response || !isset($response['id'])) {
            Log::error('Failed to create Notion claim page', ['claim_id' => $claim->id, 'response' => $response]);
            return null;
        }

        return $response['id'];
    }

    /**
     * Push a website-originated status/amount/comment change to an existing
     * Notion claim page. Only touches the 3 fields the website owns (Status,
     * Amount Awarded, Requested information) - never overwrites Notion-only
     * fields like Priority or Claim Type that the ops team manages there.
     */
    public function updateClaimPage(string $pageId, array $fields): bool
    {
        $properties = [];

        if (array_key_exists('status', $fields)) {
            $notionStatus = self::STATUS_MAP[$fields['status']] ?? null;
            if ($notionStatus) {
                $properties['Status'] = $this->status($notionStatus);
            }
        }

        if (array_key_exists('amount_approved', $fields)) {
            $properties['Amount Awarded'] = $this->number($fields['amount_approved']);
        }

        if (array_key_exists('comment', $fields) && !empty($fields['comment'])) {
            $properties['Requested information'] = $this->richText($fields['comment']);
        }

        if (empty($properties)) {
            return true;
        }

        $response = $this->request('PATCH', "/pages/{$pageId}", ['properties' => $properties]);

        if ($response === null) {
            Log::error('Failed to push claim update to Notion', ['page_id' => $pageId, 'fields' => $fields]);
            return false;
        }

        return true;
    }

    /**
     * Create the Notion page for a newly-registered client.
     */
    public function createClientPage(User $user): ?string
    {
        $databaseId = $this->clientsDatabaseId();
        if (!$databaseId) {
            Log::warning('Notion clients database id not configured, skipping push', ['user_id' => $user->id]);
            return null;
        }

        $response = $this->request('POST', '/pages', [
            'parent' => ['database_id' => $databaseId],
            'properties' => $this->clientProperties($user),
        ]);

        if (!$response || !isset($response['id'])) {
            Log::error('Failed to create Notion client page', ['user_id' => $user->id, 'response' => $response]);
            return null;
        }

        return $response['id'];
    }

    /**
     * Query claim pages edited since $sinceIso (ISO-8601 string). Returns the
     * raw array of Notion page objects, or an empty array on failure.
     */
    public function queryUpdatedClaimPages(string $sinceIso): array
    {
        $databaseId = $this->claimsDatabaseId();
        if (!$databaseId) {
            return [];
        }

        $response = $this->request('POST', "/databases/{$databaseId}/query", [
            'filter' => [
                'timestamp' => 'last_edited_time',
                'last_edited_time' => ['after' => $sinceIso],
            ],
        ]);

        return $response['results'] ?? [];
    }

    /**
     * Read the Status / Amount Awarded / Requested Information properties
     * off a raw Notion page object into a plain array, mapping the Notion
     * status option back to the website's enum value.
     */
    public function extractClaimFields(array $page): array
    {
        $props = $page['properties'] ?? [];

        $notionStatus = $props['Status']['status']['name'] ?? null;
        $status = $notionStatus ? (array_search($notionStatus, self::STATUS_MAP) ?: null) : null;

        return [
            'page_id' => $page['id'] ?? null,
            'status' => $status,
            'amount_approved' => $props['Amount Awarded']['number'] ?? null,
            'requested_information' => $this->readRichText($props['Requested information'] ?? null),
            'last_edited_time' => $page['last_edited_time'] ?? null,
        ];
    }

    private function claimProperties(Claim $claim): array
    {
        // "Airbnb Res Code" is the database's title property - Notion pages
        // need a non-empty title, so fall back to the claim number if the
        // Airbnb code hasn't been entered yet.
        $properties = [
            'Airbnb Res Code' => $this->title($claim->airbnb_reservation_code ?: $claim->claim_number),
            'claim Number ' => $this->richText($claim->claim_number),
            'Name' => $this->richText($claim->title),
            'Description' => $this->richText($claim->description),
            'Client Requested Amount' => $this->number($claim->amount_requested),
            'Amount Awarded' => $this->number($claim->amount_approved),
        ];

        $status = self::STATUS_MAP[$claim->status] ?? null;
        if ($status) {
            $properties['Status'] = $this->status($status);
        }

        if ($claim->incident_date) {
            $properties['Incident date'] = $this->date($claim->incident_date);
        }
        if ($claim->check_in_date) {
            $properties['Guest check-in date'] = $this->date($claim->check_in_date);
        }
        if ($claim->check_out_date) {
            $properties['Guest checkout date'] = $this->date($claim->check_out_date);
        }
        if ($claim->guest_name) {
            $properties['Guest Name'] = $this->richText($claim->guest_name);
        }

        // Link to the client's Notion page, if it's already been pushed
        // (it should be, since clients are pushed at registration, before
        // any claim of theirs can exist).
        if ($claim->user && $claim->user->notion_page_id) {
            $properties['Clients'] = $this->relation($claim->user->notion_page_id);
        }

        return $properties;
    }

    private function clientProperties(User $user): array
    {
        return [
            'Name' => $this->title($user->name),
            'Email' => $this->email($user->email),
            'Phone' => $this->phone($user->phone),
            // Cross-reference back to the website's internal user id.
            'Portal Client ID' => $this->richText((string) $user->id),
        ];
    }

    private function request(string $method, string $path, array $payload = []): ?array
    {
        $secret = config('services.notion.secret_key');
        if (!$secret) {
            Log::error('Notion secret key not configured');
            return null;
        }

        try {
            $response = Http::withToken($secret)
                ->withHeaders(['Notion-Version' => self::API_VERSION])
                ->{strtolower($method)}(self::API_BASE . $path, $payload);

            if (!$response->successful()) {
                Log::error('Notion API error', [
                    'path' => $path,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Notion API request failed', ['path' => $path, 'error' => $e->getMessage()]);
            return null;
        }
    }

    private function title(?string $value): array
    {
        return ['title' => [['text' => ['content' => (string) ($value ?? '')]]]];
    }

    private function richText(?string $value): array
    {
        return ['rich_text' => [['text' => ['content' => (string) ($value ?? '')]]]];
    }

    private function number($value): array
    {
        return ['number' => $value !== null ? (float) $value : null];
    }

    private function status(?string $value): array
    {
        return $value ? ['status' => ['name' => $value]] : ['status' => null];
    }

    private function relation(string $pageId): array
    {
        return ['relation' => [['id' => $pageId]]];
    }

    private function date($value): array
    {
        $date = $value instanceof \DateTimeInterface ? $value->format('Y-m-d') : $value;
        return ['date' => $date ? ['start' => $date] : null];
    }

    private function email(?string $value): array
    {
        return ['email' => $value ?: null];
    }

    private function phone(?string $value): array
    {
        return ['phone_number' => $value ?: null];
    }

    private function readRichText(?array $property): ?string
    {
        if (!$property) {
            return null;
        }
        $chunks = $property['rich_text'] ?? [];
        return collect($chunks)->pluck('plain_text')->implode('') ?: null;
    }
}
