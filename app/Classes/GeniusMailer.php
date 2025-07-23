<?php

namespace App\Classes;


use App\Models\GeneralSetting;
use Config;
use Illuminate\Support\Facades\Mail;
use PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class GeniusMailer
{
    use Queueable, SerializesModels;
    public function __construct()
    {

    }



    public function sendOrderConfirmation($order)
    {
        $setup = GeneralSetting::find(1); // Assuming this fetches your general settings

        $objDemo = new \stdClass();
        $objDemo->to = $order->user->email; // Setting the recipient to the order user's email
        $objDemo->from = $setup->from_email;
        $objDemo->title = $setup->from_name;
        $objDemo->subject = "Order Confirmation for Order #" . $order->order_number;

        try {
            Mail::send('email.orderemail', ['order' => $order], function ($message) use ($objDemo) {
                $message->from($objDemo->from, $objDemo->title);
                $message->to($objDemo->to);
                $message->subject($objDemo->subject);
            });
        } catch (\Exception $e) {
            // Handle the exception or log it
            \Log::error('Order Email Failed '. $e->getMessage());
            die('Unable to send Email Confirmation!');
            //die($e->getMessage());
        }
        return true;
    }


    public function sendWelcomeEmail($user)
    {
        $setup = GeneralSetting::find(1);

       // New Welcome Email Content for ClaimPilot+
        $welcomeMessage = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333;'>
                <h2 style='color: #1e1e2d; margin-bottom: 20px;'>Welcome to ClaimPilot+ — Let's Get You Set Up!</h2>
                
                <p>Hi {$user->name},</p>
                
                <p>Welcome aboard! We're excited to have you with us at Claim Pilot+, your white-glove Airbnb claims management service.</p>
                
                <p>To ensure a smooth and efficient onboarding, please follow the steps below. It should only take a few minutes:</p>
                
                <div style='margin: 20px 0;'>
                    <h3 style='color: #1e1e2d;'>✅ 1. Schedule Your Onboarding Call</h3>
                    <p>Choose a time that works best for you using the link below:</p>
                    <p>👉 <strong><a href='https://calendly.com/staycomfortably/owner-success-meeting' style='color: #007bff; text-decoration: none;'>https://calendly.com/staycomfortably/owner-success-meeting</a></strong></p>
                </div>
                
                <div style='margin: 20px 0;'>
                    <h3 style='color: #1e1e2d;'>✅ 2. Add Us as a Co-Host</h3>
                    <p>This allows us to file claims directly on your behalf. Please add our team to your Airbnb account using this email:</p>
                    <p>📧 <strong>Wilbertjames@staycomfortably.com</strong></p>
                    <p><strong>Step 1:</strong> Add us as a co-host on your Airbnb listing (as needed)</p>
                    <p><strong>Step 2:</strong> Once added, within 2 days we'll begin processing your claims and keep you updated.</p>
                    <p>Once added, just reply to this email to confirm.</p>
                </div>
                
                <div style='margin: 20px 0;'>
                    <h3 style='color: #1e1e2d;'>✅ 3. Send Us the Following:</h3>
                    <p>A list of your Airbnb listing names or IDs you'd like us to manage</p>
                </div>
                
                <p>If you have any questions or need help with any of the steps above, feel free to bring it up during the welcoming call!</p>
                
                <p>We're looking forward to helping you save time, reduce stress, and maximize your payouts.</p>
                
                <p>Talk soon,</p>
                <p><strong>Wilbert James</strong></p>
                
                <div style='margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 14px; color: #666;'>
                    <p>🌐 <a href='https://claimpilotplus.com' style='color: #007bff; text-decoration: none;'>Claimpilotplus.com</a></p>
                    <p>📧 <a href='mailto:Wilbertjames@staycomfortably.com' style='color: #007bff; text-decoration: none;'>Wilbertjames@staycomfortably.com</a></p>
                    <p>📞 312-772-7896</p>
                </div>
            </div>
        ";

        $mailData = [
            'to' => $user->email,
            'subject' => 'Welcome to ' . $setup->name,
            'body' => $welcomeMessage
        ];

        return $this->sendCustomMail($mailData);
    }


    public function sendCustomMail(array $mailData)
    {   
        $setup = GeneralSetting::find(1);

        $data = [
            'email_body' => $mailData['body'],
            'subject' => $mailData['subject']
        ];

        $objDemo = new \stdClass();
        $objDemo->to = $mailData['to'];
        $objDemo->from = $setup->from_email;
        $objDemo->title = $setup->from_name;
        $objDemo->subject = $mailData['subject'];

        try{
            Mail::send('email.emailbody',$data, function ($message) use ($objDemo) {
                $message->from($objDemo->from,$objDemo->title);
                $message->to($objDemo->to);
                $message->subject($objDemo->subject);
            });
        }
        catch (\Exception $e){
            die($e->getMessage());
            // return $e->getMessage();
        }
        return true;
    }

}