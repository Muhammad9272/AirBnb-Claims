<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestStripeWebhook extends Command
{
    protected $signature = 'stripe:test-webhook {url? : The webhook URL to test}';
    protected $description = 'Test if a Stripe webhook URL is accessible';

    public function handle()
    {
        $url = $this->argument('url') ?: route('stripe.webhook');
        
        $this->info("Testing webhook URL: $url");
        
        $testUrl = str_replace('/stripe/webhook', '/stripe/webhook-test', $url);
        $this->info("First testing GET endpoint: $testUrl");
        
        try {
            $response = Http::get($testUrl);
            $this->info("GET Test Status: " . $response->status());
            $this->info("GET Test Body: " . $response->body());
        } catch (\Exception $e) {
            $this->error("GET Test failed: " . $e->getMessage());
        }
        
        $this->info("\nTesting POST endpoint: $url");
        
        try {
            $response = Http::post($url, [
                'test' => true,
                'timestamp' => now()->timestamp,
                'source' => 'command-line-test'
            ]);
            $this->info("POST Test Status: " . $response->status());
            $this->info("POST Test Body: " . $response->body());
        } catch (\Exception $e) {
            $this->error("POST Test failed: " . $e->getMessage());
        }
        
        return 0;
    }
}
