<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Banner;
use App\Models\CareerEventRegistration;
use App\Models\Company;
use App\Models\Country;
use App\Models\Favorite;
use App\Models\GeneralSetting;
use App\Models\QuizBankManagement;
use App\Models\QuizProgress;
use App\Models\SubFeature;
use App\Models\SubPlan;
use App\Models\Product;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Session;
use DB;
class DashboardController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }

    public function index()
    {
        $user = auth()->user();
        $now = Carbon::now();
        
        // Basic stats - count all orders regardless of payment status
        $totalOrders = $user->orders()->count();
        
        // Total spent should only include orders with completed payments
        $totalSpent = $user->orders()
            ->where('payment_status', 'completed')
            ->sum('total');
        
        // Advanced stats - only count orders with completed payments for financial stats
        $recentOrdersCount = $user->orders()
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();
            
        // Pending orders should include both pending status and pending payments
        $pendingOrders = $user->orders()
            ->where(function($query) {
                $query->whereIn('status', ['pending', 'processing'])
                      ->orWhere('payment_status', 'pending');
            })
            ->count();
            
        // Average value of completed orders only
        $paidOrdersCount = $user->orders()->where('payment_status', 'completed')->count();
        $avgOrderValue = $paidOrdersCount > 0 ? $totalSpent / $paidOrdersCount : 0;
        
        // Recent orders for display - include payment status information
        $recentOrders = $user->orders()
            ->with(['orderItems', 'transaction'])
            ->latest()
            ->take(5)
            ->get();
            
        $gs = GeneralSetting::find(1);

        return view('user.dashboard', compact(
            'totalOrders', 
            'totalSpent', 
            'recentOrders',
            'recentOrdersCount',
            'pendingOrders',
            'paidOrdersCount',
            'avgOrderValue',
            'gs'
        ));
    }

    public function profile()
    {
        $data = Auth::user();
        $gs=GeneralSetting::find(1);
        
        $link=route('front.index').'?reff='.Auth::user()->affiliate_code;
            $title=$gs->name;
            $socialShare = \Share::page($link,$title)
            ->facebook()
            ->twitter()
            // ->reddit()
            ->linkedin()
            ->whatsapp()
            ->getRawLinks();
            // ->telegram();
        $products = Product::active()->latest()->take(2)->get();
        return view('user.profile',compact('data','socialShare','products'));
    }

    public function accountSettings()
    {
        $data = Auth::user();
        return view('user.settings.account', compact('data'));
    }

    public function accountSettingsUpdate(Request $request)
    {
        $request->validate([
            'photo' => 'nullable|mimes:jpeg,jpg,png,svg|max:2048',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
        ]);

        try {
            $user = Auth::user();
            
            if ($request->hasFile('photo')) {

                $user->photo = Helpers::update('user/avatar/', 
                    $user->photo == 'user.png' ? '' : $user->photo, 
                     config('fileformats.image'), 
                    $request->file('photo')
                );
            }

            $user->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'gender' => $request->gender,
            ]);

            return redirect()->back()->with([
                'message' => 'Profile updated successfully!',
                'alert-class' => 'alert-success'
            ]);
        } catch (\Exception $e) {
          
            \Log::error('Profile update error: ' . $e->getMessage());
            return redirect()->back()->with([
                'message' => 'Error updating profile. Please try again.',
                'alert-class' => 'alert-danger'
            ]);
        }
    }

    public function changePassword()
    {
        return view('user.settings.password');
    }

    public function changePasswordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'min:8',
                'different:current_password',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/'
            ],
            'confirm_password' => 'required|same:new_password',
        ]);

        try {
            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->with([
                    'message' => 'Current password is incorrect',
                    'alert-class' => 'alert-danger'
                ]);
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return redirect()->back()->with([
                'message' => 'Password changed successfully!',
                'alert-class' => 'alert-success'
            ]);
        } catch (\Exception $e) {
            \Log::error('Password change error: ' . $e->getMessage());
            return redirect()->back()->with([
                'message' => 'Error changing password. Please try again.',
                'alert-class' => 'alert-danger'
            ]);
        }
    }

    public function resetform()
    {
        return view('user.cpassword');
    }

    public function reset(Request $request)
    {
       $request->validate([
         'cpass'=>'required',
         'newpass'=>'required',
         'renewpass' => 'required|same:newpass'
        ]);
        $user = Auth::user();
        if ($request->cpass){
            if (Hash::check($request->cpass, $user->password)){
                    $input['password'] = Hash::make($request->newpass);
            }else{
               Session::flash('message', 'Current password Does not match.');
               Session::flash('alert-class', 'alert-danger');
               return redirect()->back();
            }
        }
        $user->update($input);
        Session::flash('message', 'Successfully change your password');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function logoutOtherDevices(Request $request)
    {
        try {
            // Get current session ID
            $currentSessionId = $request->session()->getId();
            
            // Revoke all sessions except the current one
            DB::table('sessions')
                ->where('user_id', auth()->id())
                ->where('id', '!=', $currentSessionId)
                ->delete();
            
            Session::flash('message', 'You have been logged out from all other devices.');
            Session::flash('alert-class', 'alert-success');
        } catch (\Exception $e) {
            \Log::error('Error in logoutOtherDevices: ' . $e->getMessage());
            Session::flash('message', 'There was an error processing your request. Please try again later.');
            Session::flash('alert-class', 'alert-danger');
        }
        
        return redirect()->back();
    }

    public function storeMedia(Request $request)
    {
        $path = storage_path('tmp/uploads');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');
        $name = uniqid() . '_' . trim($file->getClientOriginalName());
        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function pricing()
    {  
       $subplans=SubPlan::active()->get(); 
       $subfeatures=SubFeature::active()->get();
       return view('user.subplans.pricing',compact('subplans','subfeatures'));
    }
    

    public function help()
    {
       return view('user.help');
    }



    public function customerSupport()
    {  

        $id=1;
        $type='user';
        $messengerColor='#2180f3';
        $dark_mode='light';
        return view('user.livechat.show',compact('id','type','messengerColor','dark_mode'));
    }


}
