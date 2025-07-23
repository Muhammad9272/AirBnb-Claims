<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Helpers;

use App\Http\Controllers\Controller;
use App\Models\EmailVerifications;
use App\Models\GeneralSetting;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Session;
use App\Classes\GeniusMailer;
use Illuminate\Support\Facades\Log;
class LoginController extends Controller
{

    public function __construct()
    {
         $this->middleware('guest')->only('showLoginForm','authenticationToken','newAuthenticationToken');
    }

    public function showLoginForm()
    {
      return view('user.auth.login');
    }

    public function login(Request $request)
    {

        $this->validate($request,
        [
                  'email'   => 'required|email',
                  'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->where('role_type','user')->first();
        if (isset($user) && password_verify($request->password, $user->password) ) {

          $gs=GeneralSetting::find(1);
          if($user->status!=1){
            return back()->with('error','Your account has been blocked.');
          }
          if($gs->email_verification==1 && $user->is_email_verified!=1){
            $response=Helpers::send_verification_otp($user->email);
            if(isset($response['success'])){
              return redirect()->back()->with(['showVerificationModal' => true, 'email' => $user->email]);
            }else{ return redirect()->back()->with('error',$response['error']);}
          }else{
             // Auth::guard('web')->login($user);
              if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
                    // if successful, then redirect to their intended location
                   return redirect()->intended(route('front.index'));
               }
          }
        }
        Session::flash('message', 'Credentials Doesn\'t Match !');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->back()->with('error','Credentials Doesn\'t Match !');

    }
    public function logout($value='')
    {
        Auth::guard('web')->logout();
        return redirect()->route('front.index');
    }


    public function authenticationToken(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return view('errors.404');
        }
        $verify = EmailVerifications::where([
            'email' => $request->email,
            'token' => $request->token
        ])->first();

        if ($verify) {
            $verify->delete();
            $user->is_email_verified=1;
            $user->update();
            Auth::guard('web')->login($user);

             // Send welcome email after successful registration
            try {
                $mailer = new GeniusMailer();
                $mailer->sendWelcomeEmail($user);
            } catch (\Exception $e) {
                // Log the error but don't stop the registration process
                Log::error('Welcome email failed: ' . $e->getMessage());
            }


             // Redirect to the intended route or fallback to dashboard
            $redirectRoute = session('url.intended', route('user.dashboard'));

            return response()->json([
                'success' => true,
                'msg' => 'Account verified',
                'route' => $redirectRoute // Use the intended route or fallback
        ]);
          
        } else {
            return response()->json(['error' => 'Invalid Code !']);
        }
    }


    public function newAuthenticationToken($email){
          $user = User::where('email', $email)->first();
          $gs=GeneralSetting::find(1);
          if($user && $gs->email_verification==1 && $user->is_email_verified!=1){
            $response=Helpers::send_verification_otp($user->email);
            if(isset($response['success'])){
              return response()->json(['success' => $response['success'] ]);
            }else{  return response()->json(['error' => $response['error'] ]); }
          }
    }

    protected function authenticated(Request $request, $user)
    {
        // Update last login timestamp
        $user->update([
            'last_login' => now()
        ]);
        
        // Continue with normal login flow
        return redirect()->intended(route('user.dashboard'));
    }
}
