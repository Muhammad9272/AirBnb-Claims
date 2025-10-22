<?php

namespace App;

use App\Helper;
use App\Models\GeneralSetting;
use App\Models\User;
use Cookie;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Session;
use App\Classes\GeniusMailer;
use Illuminate\Support\Facades\Log;
class SocialAccountService
{


    public function createOrGetUser(ProviderUser $providerUser, $provider )
    {
      $settings = GeneralSetting::first();

      $user = User::whereOauthProvider($provider)
          ->whereOauthUid($providerUser->getId())
          ->first();

      if (! $user) {
        //return 'Error! Your email is required, Go to app settings and delete our app and try again';

        if (! $providerUser->getEmail()) {
          return redirect()->route("user.login")->with('error','error.error_required_mail');
          exit;
        }

        //Verify Email user
        $userEmail = User::whereEmail($providerUser->getEmail())->first();

        if ($userEmail) {
         return redirect()->route("user.login")->with('error','Email Already Exists');
          exit;
        }

        $token = Str::random(75);

        $avatar = 'user.png';
        $nameAvatar = time().$providerUser->getId();
        // $path = config('path.avatar');


            if ($settings->email_verification == '1') {
              $verify = 1;
            } else {
              $verify = 1;
            }



				$user = User::create([
                'name'              => $providerUser->getName(),
                'email'             => strtolower($providerUser->getEmail()),
                'password'          => bcrypt($providerUser->getName()),
                'oauth_uid'         => $providerUser->getId(),
                'oauth_provider'    => $provider,
                'role_id'           => 1
			  ]);
        // Update User
        $user->role_id=1;
        $user->is_email_verified=$verify;
        $user->update();

        // Handle affiliate & referral tracking (single function call)
        User::handleReferralTracking($user);


        // Send welcome email after successful registration
        try {
            $mailer = new GeniusMailer();
            $mailer->sendWelcomeEmail($user);
        } catch (\Exception $e) {
            // Log the error but don't stop the registration process
            Log::error('Welcome email failed: ' . $e->getMessage());
        }

        
        
    }// !$user
        return $user;
    }
}
