<?php

namespace App\Http\Controllers\User;

// use App\CentralLogics\Cart;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\GeneralSetting;
use App\Models\User;
use Auth;
use Cookie;
use Illuminate\Http\Request;
use Session;

use App\Classes\GeniusMailer;
use Illuminate\Support\Facades\Log;
class RegisterController extends Controller
{
    public function __construct()
    {
         $this->middleware('guest');
    }

    public function showRegisterForm()
    {  
        return view('user.auth.register');
    }

    public function register(Request $request)
    {

        //--- Validation Section
        $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'unique:users,name',
                'regex:/^[a-zA-Z0-9_\-\.]+$/', // Only alphanumeric, underscore, hyphen, and dot
                'not_regex:/(http|https|ftp|www\.|\.com|\.net|\.org|\.io|@)/i', // Block URLs and email-like patterns
                function ($attribute, $value, $fail) {
                    // Additional custom validation
                    
                    // Check for URL patterns
                    if (preg_match('/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i', $value)) {
                        $fail('The username cannot contain URLs or links.');
                    }
                    
                    // Check for common spam patterns
                    $spamPatterns = [
                        '/\.(com|net|org|io|co|uk|ca|de|jp|fr|au|us|ru|ch|it|nl|se|no|es|mil|edu|gov|biz|info|xyz|online|site|website|link|url)/i',
                        '/(?:viagra|cialis|pharma|casino|poker|porn|xxx|sex|dating|loan|mortgage|credit|bitcoin|crypto|forex|binary)/i',
                        '/[<>\"\'%;()&+]/i', // HTML/SQL injection characters
                    ];
                    
                    foreach ($spamPatterns as $pattern) {
                        if (preg_match($pattern, $value)) {
                            $fail('The username contains invalid characters or patterns.');
                        }
                    }
                    
                    // Check for excessive special characters
                    $specialCharCount = preg_match_all('/[^a-zA-Z0-9]/', $value);
                    if ($specialCharCount > 2) {
                        $fail('The username contains too many special characters.');
                    }
                    
                    // Prevent consecutive special characters
                    if (preg_match('/[^a-zA-Z0-9]{2,}/', $value)) {
                        $fail('The username cannot have consecutive special characters.');
                    }
                }
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns', // Stricter email validation with DNS check
                'max:255',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    // Block disposable email domains
                    $disposableDomains = [
                        'mailinator.com', 'guerrillamail.com', '10minutemail.com',
                        'tempmail.com', 'throwaway.email', 'yopmail.com'
                        // Add more disposable domains as needed
                    ];
                    
                    $domain = substr(strrchr($value, "@"), 1);
                    if (in_array($domain, $disposableDomains)) {
                        $fail('Please use a valid email address.');
                    }
                }
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', // At least one uppercase, lowercase, and number
            ]
        ], [
            'name.required' => 'Username is required.',
            'name.unique' => 'The username has already been taken.',
            'name.regex' => 'Username can only contain letters, numbers, underscores, hyphens, and dots.',
            'name.not_regex' => 'Username cannot contain URLs or invalid patterns.',
            'name.min' => 'Username must be at least 3 characters.',
            'name.max' => 'Username cannot exceed 30 characters.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);
            $gs=GeneralSetting::find(1);


          $user = new User;
          $input = $request->all();
          $input['role_id']=1;
          $input['password'] = bcrypt($request['password']);

            

          $user->fill($input)->save();
           // Handle affiliate & referral tracking (single function call)
          User::handleReferralTracking($user);


          if($gs->email_verification==1){
            $response=Helpers::send_verification_otp($user->email);
            if(isset($response['success'])){
              return redirect()->back()->with(['showVerificationModal' => true, 'email' => $user->email ]);
            }else{ return redirect()->back()->with('error',$response['error']);}
          }else{
             $user->is_email_verified=1;
             $user->update();

             // Send welcome email after successful registration
            try {
                $mailer = new GeniusMailer();
                $mailer->sendWelcomeEmail($user);
            } catch (\Exception $e) {
                // Log the error but don't stop the registration process
                Log::error('Welcome email failed: ' . $e->getMessage());
            }
          }
          //clear cart
          //Cart::clearCart();
          Auth::guard('web')->login($user);
          $redirectTo=route('user.dashboard');
           return redirect()->intended(route('user.dashboard'))->with('success','Acount Registered Successfully');
          // return redirect()->to($redirectTo)->with('success','Acount Registered Successfully');

    }
}
