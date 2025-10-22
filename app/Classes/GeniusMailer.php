<?php

namespace App\Classes;


use App\Models\GeneralSetting;
use Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
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

    public function sendLeadDiscountEmail($lead, $discountCode, $discountPercentage)
    {
        $setup = GeneralSetting::find(1);

        $discountMessage = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333;'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <h1 style='color: #1e1e2d; margin-bottom: 10px;'>🎉 Your Exclusive Discount is Here!</h1>
                    <p style='color: #666; font-size: 18px;'>Thank you for your interest in " . $setup->name . "!</p>
                </div>
                
                <p>Hi {$lead->name},</p>
                
                <p>Welcome! We're excited that you're interested in our Airbnb claims management service.</p>
                
                <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 15px; text-align: center; margin: 30px 0;'>
                    <h2 style='margin: 0 0 10px 0; color: white;'>Your Exclusive Discount Code</h2>
                    <div style='background: rgba(255,255,255,0.2); padding: 15px; border-radius: 10px; margin: 20px 0;'>
                        <div style='font-size: 32px; font-weight: bold; letter-spacing: 3px; margin-bottom: 5px;'>{$discountCode}</div>
                        <div style='font-size: 18px; opacity: 0.9;'>{$discountPercentage}% OFF Your First Subscription</div>
                    </div>
                    <p style='margin: 15px 0 0 0; font-size: 14px; opacity: 0.8;'>Use this code at checkout to save on your first subscription!</p>
                </div>
                
                <div style='margin: 30px 0;'>
                    <h3 style='color: #1e1e2d; margin-bottom: 15px;'>Why Choose " . $setup->name . "?</h3>
                    <ul style='list-style: none; padding: 0;'>
                        <li style='margin: 10px 0; padding-left: 25px; position: relative;'>
                            <span style='position: absolute; left: 0; color: #10b981;'>✓</span>
                            <strong>Expert Claims Management:</strong> We handle all your Airbnb damage and security deposit claims
                        </li>
                        <li style='margin: 10px 0; padding-left: 25px; position: relative;'>
                            <span style='position: absolute; left: 0; color: #10b981;'>✓</span>
                            <strong>Higher Success Rate:</strong> Our experienced team knows exactly how to get claims approved
                        </li>
                        <li style='margin: 10px 0; padding-left: 25px; position: relative;'>
                            <span style='position: absolute; left: 0; color: #10b981;'>✓</span>
                            <strong>Save Time & Stress:</strong> No more dealing with lengthy Airbnb claim processes
                        </li>
                        <li style='margin: 10px 0; padding-left: 25px; position: relative;'>
                            <span style='position: absolute; left: 0; color: #10b981;'>✓</span>
                            <strong>Commission-Based:</strong> We only get paid when you get paid
                        </li>
                    </ul>
                </div>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='" . url('/subscription/plans') . "' style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;'>
                        View Subscription Plans →
                    </a>
                </div>
                
                <div style='background: #f8f9ff; padding: 20px; border-radius: 10px; margin: 30px 0;'>
                    <h4 style='color: #1e1e2d; margin: 0 0 10px 0;'>🕐 Limited Time Offer</h4>
                    <p style='margin: 0; color: #666;'>This discount code is valid for your first subscription only. Don't miss out on this exclusive savings!</p>
                </div>
                
                <p>Ready to get started? Simply use code <strong>{$discountCode}</strong> at checkout to claim your {$discountPercentage}% discount.</p>
                
                <p>If you have any questions, feel free to reach out to our support team.</p>
                
                <p>Best regards,<br>
                <strong>The " . $setup->name . " Team</strong></p>
                
                <div style='margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; font-size: 14px; color: #666; text-align: center;'>
                    <p>🌐 <a href='" . url('/') . "' style='color: #667eea; text-decoration: none;'>" . $setup->name . "</a></p>
                    <p style='margin: 5px 0;'>You received this email because you signed up for our discount on our website.</p>
                    <p style='font-size: 12px; color: #999;'>If you no longer wish to receive these emails, you can unsubscribe at any time.</p>
                </div>
            </div>
        ";

        $mailData = [
            'to' => $lead->email,
            'subject' => '🎉 Your Exclusive ' . $discountPercentage . '% Discount Code - ' . $setup->name,
            'body' => $discountMessage
        ];

        return $this->sendCustomMail($mailData);
    }

    public function sendInfluencerCredentials($user, $password)
    {
        $setup = GeneralSetting::find(1);

        $credentialsMessage = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333;'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <h1 style='color: #1e1e2d; margin-bottom: 10px;'>🎉 Welcome to Our Partner Program!</h1>
                    <p style='color: #666; font-size: 18px;'>You've been invited as an Influencer Partner</p>
                </div>
                
                <p>Hi {$user->name},</p>
                
                <p>Congratulations! You've been added as an <strong>Influencer Partner</strong> for " . $setup->name . ". We're excited to have you on board!</p>
                
                <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 15px; text-align: center; margin: 30px 0;'>
                    <h2 style='margin: 0 0 20px 0; color: white;'>Your Login Credentials</h2>
                    <div style='background: rgba(255,255,255,0.2); padding: 20px; border-radius: 10px; margin: 20px 0;'>
                        <div style='margin-bottom: 15px;'>
                            <strong style='display: block; margin-bottom: 5px;'>Email:</strong>
                            <span style='font-size: 18px;'>{$user->email}</span>
                        </div>
                        <div style='margin-bottom: 15px;'>
                            <strong style='display: block; margin-bottom: 5px;'>Password:</strong>
                            <span style='font-size: 18px; letter-spacing: 2px; background: rgba(255,255,255,0.2); padding: 8px 15px; border-radius: 5px;'>{$password}</span>
                        </div>
                        <div style='border-top: 1px solid rgba(255,255,255,0.3); padding-top: 15px; margin-top: 15px;'>
                            <strong style='display: block; margin-bottom: 5px;'>Your Affiliate Code:</strong>
                            <span style='font-size: 22px; letter-spacing: 3px; background: rgba(255,255,255,0.3); padding: 10px 20px; border-radius: 8px; font-weight: bold; display: inline-block;'>{$user->affiliate_code}</span>
                        </div>
                    </div>
                    <p style='margin: 15px 0 0 0; font-size: 14px; opacity: 0.8;'>Please change your password after first login</p>
                </div>
                
                <div style='background: #f0f7ff; padding: 20px; border-radius: 10px; margin: 30px 0; border-left: 4px solid #667eea;'>
                    <h4 style='color: #1e1e2d; margin: 0 0 15px 0;'>🔗 Your Affiliate Link</h4>
                    <p style='margin: 5px 0; color: #666; word-break: break-all;'>
                        <a href='" . url('/?ref=' . $user->affiliate_code) . "' style='color: #667eea; text-decoration: none;'>" . url('/?ref=' . $user->affiliate_code) . "</a>
                    </p>
                    <p style='margin: 15px 0 0 0; font-size: 14px; color: #666;'>Share this link with your audience to track referrals and earn commissions!</p>
                </div>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='" . route('user.login') . "' style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;'>
                        Login to Your Dashboard →
                    </a>
                </div>
                
                <div style='margin: 30px 0;'>
                    <h3 style='color: #1e1e2d; margin-bottom: 15px;'>As an Influencer Partner, you can:</h3>
                    <ul style='list-style: none; padding: 0;'>
                        <li style='margin: 10px 0; padding-left: 25px; position: relative;'>
                            <span style='position: absolute; left: 0; color: #10b981;'>✓</span>
                            <strong>Track Your Referrals:</strong> Monitor customers who sign up using your affiliate link
                        </li>
                        <li style='margin: 10px 0; padding-left: 25px; position: relative;'>
                            <span style='position: absolute; left: 0; color: #10b981;'>✓</span>
                            <strong>Earn Commissions:</strong> Get 10% commission on approved claims from your referrals
                        </li>
                        <li style='margin: 10px 0; padding-left: 25px; position: relative;'>
                            <span style='position: absolute; left: 0; color: #10b981;'>✓</span>
                            <strong>View Earnings Report:</strong> See detailed commission reports in your dashboard
                        </li>
                        <li style='margin: 10px 0; padding-left: 25px; position: relative;'>
                            <span style='position: absolute; left: 0; color: #10b981;'>✓</span>
                            <strong>Get Your Unique Link:</strong> Access your personalized affiliate link for sharing
                        </li>
                    </ul>
                </div>
                
                <div style='background: #f8f9ff; padding: 20px; border-radius: 10px; margin: 30px 0;'>
                    <h4 style='color: #1e1e2d; margin: 0 0 10px 0;'>📈 Commission Structure</h4>
                    <p style='margin: 5px 0; color: #666;'><strong>Rate:</strong> 10% commission on approved claims</p>
                    <p style='margin: 5px 0; color: #666;'><strong>Duration:</strong> Commission applies for 30 days from customer's subscription date</p>
                    <p style='margin: 5px 0; color: #666;'><strong>Payment:</strong> Monthly payouts for qualified commissions</p>
                </div>
                
                <div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                    <h4 style='color: #856404; margin: 0 0 10px 0;'>🔐 Important Security Note</h4>
                    <p style='margin: 0; color: #856404;'>Please change your password immediately after your first login for security purposes.</p>
                </div>
                
                <p>If you have any questions about the partner program or need assistance getting started, feel free to reach out to our support team.</p>
                
                <p>Welcome aboard!<br>
                <strong>The " . $setup->name . " Team</strong></p>
                
                <div style='margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; font-size: 14px; color: #666; text-align: center;'>
                    <p>🌐 <a href='" . url('/') . "' style='color: #667eea; text-decoration: none;'>" . $setup->name . "</a></p>
                    <p style='margin: 5px 0;'>You received this email because you were added as an Influencer Partner.</p>
                    <p style='font-size: 12px; color: #999;'>If you have any questions, please contact our support team.</p>
                </div>
            </div>
        ";

        $mailData = [
            'to' => $user->email,
            'subject' => '🎉 Welcome to Our Influencer Partner Program - Login Credentials',
            'body' => $credentialsMessage
        ];

        return $this->sendCustomMail($mailData);
    }


    public function sendCustomMail(array $mailData)
    {   
        $setup = GeneralSetting::find(1);

        if (!$setup || !$setup->from_email) {
            Log::error('GeniusMailer: General settings or from_email not configured');
            throw new \Exception('Email configuration is missing. Please configure email settings in general settings.');
        }

        $data = [
            'email_body' => $mailData['body'],
            'subject' => $mailData['subject']
        ];

        $objDemo = new \stdClass();
        $objDemo->to = $mailData['to'];
        $objDemo->from = $setup->from_email;
        $objDemo->title = $setup->from_name ?? 'ClaimPilot+';
        $objDemo->subject = $mailData['subject'];

        try{
            Mail::send('email.emailbody',$data, function ($message) use ($objDemo) {
                $message->from($objDemo->from,$objDemo->title);
                $message->to($objDemo->to);
                $message->subject($objDemo->subject);
            });
            
            Log::info('Email sent successfully to: ' . $objDemo->to);
            return true;
        }
        catch (\Exception $e){
            Log::error('GeniusMailer Error: ' . $e->getMessage());
            Log::error('Email Details - To: ' . $objDemo->to . ', From: ' . $objDemo->from . ', Subject: ' . $objDemo->subject);
            throw $e;
        }
    }

}