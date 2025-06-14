<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Notification;
use App\Classes\GeniusMailer;
use Illuminate\Support\Facades\Log;

class SendNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notificationId;
    protected $email;
    protected $subject;
    protected $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notificationId, $email, $subject, $body)
    {
        $this->notificationId = $notificationId;
        $this->email = $email;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Check if notification still exists and is unread
            $notification = Notification::find($this->notificationId);
            
            if ($notification && !$notification->is_read) {
                $mailer = new GeniusMailer();
                $mailer->sendCustomMail([
                    'to' => $this->email,
                    'subject' => $this->subject,
                    'body' => $this->body
                ]);
                
                Log::info('Notification email sent for notification #' . $this->notificationId);
            } else {
                Log::info('Notification email skipped - notification is read or deleted');
            }
        } catch (\Exception $e) {
            Log::error('Failed to send notification email: ' . $e->getMessage());
        }
    }
}
