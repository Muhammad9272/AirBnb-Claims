<?php

namespace App;

use App\Helper;
use App\Models\GeneralSetting;
use App\Models\User;
use Cookie;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Session;
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

        if ($settings->is_affilate == 1) {
          $referred_by='';
          if(Session::has('affilate')){
            $referred_by=Session::get('affilate');
          }

          $affiliate_code=substr(uniqid(), 0, 8);
          while($affiliate_user=User::where('affiliate_code', '=', $affiliate_code)->exists())
          {
           $affiliate_code=substr(uniqid(), 0, 8);
          }
          $user->update([
            'affiliate_code' =>$affiliate_code,
            'referred_by' =>$referred_by,
          ]);
        }
        
    }// !$user
        return $user;
    }
}
