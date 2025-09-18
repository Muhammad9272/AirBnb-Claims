<?php

namespace App\CentralLogics;

use App\Classes\GeniusMailer;
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class Helpers
{


     public static function upload(string $dir, string $format, $file = null)
    {
        if($file == null){
            //return 'def.png';
            return null;
        }
        if (!$file->isValid()) {
            throw new \Exception('Invalid file.');
        }
        if (!in_array(strtolower($file->getClientOriginalExtension()), explode('|', $format))) {
            //die('Invalid file format. Allowed formats: '.$format);
            throw new \Exception('Invalid file format. Allowed formats: '.$format);
        }
        
        if (!file_exists(public_path('assets/dynamic/images/' . $dir))) {
            mkdir(public_path('assets/dynamic/images/' . $dir), 0777, true);
        }
        $name = time().$file->getClientOriginalName();
        $name=str_replace(' ','',$name);        
        $file->move(public_path('assets/dynamic/images/'.$dir), $name);
        return $name;
    }
    public static function duplicateFile($filePath, $targetDirectory)
    {
        if ($filePath == null) {
            return null;
        }

        $sourcePath = public_path('assets/dynamic/images/' .$targetDirectory .$filePath);
        if (!file_exists($sourcePath)) {
            throw new \Exception('Source file does not exist.');
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $fileName = time() . Str::random(8) . '.' . $extension;

        $targetPath = public_path('assets/dynamic/images/' . $targetDirectory . $fileName);
        if (!file_exists(public_path('assets/dynamic/images/' . $targetDirectory))) {
            mkdir(public_path('assets/dynamic/images/' . $targetDirectory), 0777, true);
        }

        // Copy the file to the new location
        copy($sourcePath, $targetPath);

        return $fileName;
    }

    public static function update(string $dir,string $old_image=null, string $format, $file = null)
    {   
        if($file == null){
            return $old_image;
        }
        if($old_image != null && file_exists(public_path('assets/dynamic/images/' . $dir . $old_image))) {
                unlink(public_path('assets/dynamic/images/' . $dir . $old_image));
        }
        return Helpers::upload($dir, $format, $file);
    }
    public static function unlink(string $dir,string $old_image)
    {   

        if($old_image != null && file_exists(public_path('assets/dynamic/images/' . $dir . $old_image))) {
                unlink(public_path('assets/dynamic/images/' . $dir . $old_image));
        };
    }   
    public static function image($file, $dir, $default = 'def.png')
    {
        $image = ($file && file_exists(public_path('/assets/dynamic/images/'.$dir.$file)) )  ? asset('/assets/dynamic/images/'.$dir.$file) : asset('/images/'.$default);
        return $image;
    }
    public static function slug(string $slug)
    {
        //return $slug=Str::slug($slug,'-').'-'.strtolower(Str::random(3));
         return $slug=Str::slug($slug,'-');
    }

    public static function getGreeting($value='')
    {
        $currentTime = Carbon::now();
        $hour = $currentTime->hour;

        if ($hour >= 5 && $hour < 12) {
            return 'Good Morning';
        } elseif ($hour >= 12 && $hour < 18) {
            return 'Good Afternoon';
        } else {
            return 'Good Evening';
        }
    }


    

    public static function send_verification_otp($email)
    {   
     
        $token=rand(1000, 9999);
        $autopass = Str::random(64);
            DB::table('email_verifications')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            try {
                $gs = GeneralSetting::findOrFail(1);
                if($gs->is_smtp == 1)
                {   
                   $to = $email;
                   $subject = "Verify your email address.";
                   $msg = "Dear Customer,<br> We noticed that you need to verify your email address.This is your  Verification Code " .$token;

                   $data = [
                                'to' => $to,
                                'subject' => $subject,
                                'body' => $msg,
                            ];
                        $mailer = new GeniusMailer();
                        $mailer->sendCustomMail($data);              
                }
                else
                {
                    $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                    mail($request->email,$subject,$msg,$headers);            
                }
                return $response=array('success' =>'Code Resend SuccessFully');

            } catch (\Exception $e) {
                return $response=array('error'=>$e->getMessage());   
            }

    }

    public static function envUpdate($key, $value, $comma = false)
    {
      $path = base_path('.env');
            $value = trim($value);
            $env = $comma ? '"'.env($key).'"' : env($key);

      if (file_exists($path)) {

          file_put_contents($path, str_replace(
              $key . '=' . $env,
                            $key . '=' . $value,
                            file_get_contents($path)
          ));
      }
    }

    public static function setInterval($interval) {
       return ucfirst($interval);
    }



    public static function setCurrency($price) {
         return self::generalVariables('currency_symbol') . self::getPrice($price);
    }
    public static function formatPrice($price) {
         return self::setCurrency($price);
    }
    
    
    public static function getCurrencySymbol()
    {
       return self::generalVariables('currency_symbol');
    }
    public static function getCurrency()
    {
       return self::generalVariables('currency');
    }

    public static function getPrice($price, $number_format = 1)
    {
        if ($number_format == 1) {
            // Display: "1400.00" (no thousands separator)
            return number_format((float)$price, 2, '.', '');
        }

        // Backend/DB: raw float rounded to 2 decimal places (no string)
        return round((float)$price, 2);
    }

    public static function generalVariables($key = null)
    {
        $vars = [
            'currency_symbol' => '$',
            'currency' => 'USD',
        ];


        if ($key) {
            return $vars[$key] ?? null; // return value if key exists, else null
        }

        return $vars; // if no key passed, return full array
    }

    


    public static function getTaxRate()
    {
        return GeneralSetting::first()->tax_rate ?? 0.10; // 10% default
    }

    public static function getShippingCost()
    {
        return GeneralSetting::first()->shipping_cost ?? 0;
    }

    public static function calculateShipping($totalItems)
    {
        $gs = GeneralSetting::first();
        $baseShipping = $gs->shipping_cost ?? 5.00;
        
        // Add $1 for each item after the first one, up to a maximum of $15 total shipping
        $additionalShipping = min(($totalItems - 1) * 1.00, 10.00);
        return $baseShipping + ($totalItems > 1 ? $additionalShipping : 0);
    }

    public static function getCountries()
    {
        return [
            'US' => 'United States',
            'CA' => 'Canada',
            'MX' => 'Mexico',
            'GB' => 'United Kingdom',
            'DE' => 'Germany',
            'FR' => 'France',
            'IT' => 'Italy',
            'ES' => 'Spain',
            'AU' => 'Australia',
            'NZ' => 'New Zealand',
            'JP' => 'Japan',
            'CN' => 'China',
            'IN' => 'India',
            'BR' => 'Brazil',
            'AR' => 'Argentina',
            'ZA' => 'South Africa',
            'AE' => 'United Arab Emirates',
            'SG' => 'Singapore',
            'MY' => 'Malaysia',
            'PH' => 'Philippines',
            'TH' => 'Thailand',
            'VN' => 'Vietnam',
            'ID' => 'Indonesia',
            'PK' => 'Pakistan',
            'BD' => 'Bangladesh',
            'RU' => 'Russia',
            'UA' => 'Ukraine',
            'TR' => 'Turkey',
            'SA' => 'Saudi Arabia',
            'EG' => 'Egypt',
            'NG' => 'Nigeria',
            'KE' => 'Kenya',
            'ZA' => 'South Africa'
        ];
    }

    

}


