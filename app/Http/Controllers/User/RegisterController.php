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
                'name' => 'required|unique:users,name',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed'
            ],[
                'name.unique' => 'The username has already been taken.'
            ]);
            $gs=GeneralSetting::find(1);


          $user = new User;
          $input = $request->all();
          $input['role_id']=1;
          $input['password'] = bcrypt($request['password']);

            if ($gs->is_affilate == 1) {
              $referred_by='';
              if(Session::has('affilate')){
                $referred_by=Session::get('affilate');
              }

              $affiliate_code=substr(uniqid(), 0, 8);
              while(User::where('affiliate_code', '=', $affiliate_code)->exists())
              {
               $affiliate_code=substr(uniqid(), 0, 8);
              }

              $input['affiliate_code'] = $affiliate_code;
              $input['referred_by'] = $referred_by;
            }

          $user->fill($input)->save();


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
