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