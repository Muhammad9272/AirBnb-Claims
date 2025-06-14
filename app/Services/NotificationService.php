<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Classes\GeniusMailer;
use App\Jobs\SendNotificationEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a notification and send email if needed
     */
    public static function notify($user, $type, $title, $message, $link = null, $sendEmail = true)
    {
        try {
            // Create in-app notification
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'link' => $link
            ]);
            
            // Queue email to be sent after 5 minutes if notification remains unread
            if ($sendEmail && $user->email) {
                SendNotificationEmail::dispatch($notification->id, $user->email, $title, $message)
                    ->delay(Carbon::now()->addMinutes(5));
            }
            
            return $notification;
        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Notify admin about an event
     */
    public static function notifyAdmin($type, $title, $message, $link = null)
    {
        try {
           
            // Get admin users
            $admin = User::admin()
                          ->first();
            // foreach ($admins as $admin) {
                // Create in-app notification for admin
                self::notify($admin, $type, $title, $message, $link);
            // }
        } catch (\Exception $e) {
            Log::error('Failed to notify admin: ' . $e->getMessage());
        }
    }
    
    /**
     * Send notifications about claim status changes
     */
    public static function claimStatusChanged($claim, $oldStatus, $newStatus, $notes = null)
    {
        $user = $claim->user;
        $title = "Claim Status Updated";
        $message = "Your claim #{$claim->claim_number} status has been changed from " . 
                   ucfirst(str_replace('_', ' ', $oldStatus)) . 
                   " to " . ucfirst(str_replace('_', ' ', $newStatus)) . ".";
                   
        if ($notes) {
            $message .= "\n\nNote: " . $notes;
        }
        
        $link = route('user.claims.show', $claim->id);
        
        self::notify($user, 'claim_status_changed', $title, $message, $link);
    }
    
    /**
     * Send notifications about new comments
     */
    public static function newComment($comment)
    {
        $claim = $comment->claim;
        $user = $claim->user;
        
        // If admin commented, notify user
        if ($comment->is_admin) {
            $title = "New Comment on Your Claim";
            $message = "An administrator has added a comment to your claim #{$claim->claim_number}.";
            $link = route('user.claims.show', $claim->id);
            
            self::notify($user, 'new_comment', $title, $message, $link);
        } 
        // If user commented, notify admin
        else {
            $title = "New User Comment on Claim #{$claim->claim_number}";
            $message = "User {$user->name} has added a comment to claim #{$claim->claim_number}.";
            $link = route('admin.claims.show', $claim->id);
            
            self::notifyAdmin('new_user_comment', $title, $message, $link);
        }
    }
    
    /**
     * Send notifications about new evidence
     */
    public static function newEvidence($claim, $filesCount)
    {
        $title = "New Evidence Added to Claim #{$claim->claim_number}";
        $message = "User {$claim->user->name} has added {$filesCount} new file(s) as evidence to claim #{$claim->claim_number}.";
        $link = route('admin.claims.show', $claim->id);
        
        self::notifyAdmin('new_evidence', $title, $message, $link);
    }
}
