<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\Product;
use App\Models\SocialSetting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\BitTransaction;
use App\Models\BitSubmission;
use App\Models\OrderItem;
use App\Models\ProductReview;
use App\Models\Claim;
use App\Models\UserSubscription;
use App\Models\SubPlan;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use App\CentralLogics\SalesAnalytics;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
       
        // Get counts for key metrics
        $totalUsers = User::count();
        $totalClaims = Claim::count();
        $totalSubscriptions = UserSubscription::where('status', 'active')->count();
        $totalPlans = SubPlan::where('status', 1)->count();
        
        // Claims statistics
        $pendingClaims = Claim::where('status', 'pending')->count();
        $approvedClaims = Claim::where('status', 'approved')->count();
        $rejectedClaims = Claim::where('status', 'rejected')->count();
        $inProgressClaims = Claim::where('status', 'in_progress')->count();
        
        // Calculate approval rate
        $approvalRate = $totalClaims > 0 ? round(($approvedClaims / $totalClaims) * 100, 1) : 0;
        
        // Get recent claims (last 10)
        $recentClaims = Claim::with('user')
                            ->orderBy('created_at', 'desc')
                            ->limit(10)
                            ->get();
        
        // Get new users (last 10)
        $newUsers = User::orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
        
        // Get monthly revenue data for chart (last 6 months)
        $monthlyRevenue = $this->getMonthlyRevenue();
        
        // Get subscription plan distribution
        $planDistribution = $this->getPlanDistribution();
        
        // Get claims by status for chart
        $claimsByStatus = [
            'pending' => $pendingClaims,
            'in_progress' => $inProgressClaims,
            'approved' => $approvedClaims,
            'rejected' => $rejectedClaims
        ];
        
        // Get weekly claims data
        $weeklyClaims = $this->getWeeklyClaims();
        
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalClaims', 
            'totalSubscriptions', 
            'totalPlans',
            'pendingClaims',
            'approvedClaims',
            'rejectedClaims',
            'inProgressClaims',
            'approvalRate',
            'recentClaims',
            'newUsers',
            'monthlyRevenue',
            'planDistribution',
            'claimsByStatus',
            'weeklyClaims'
        ));
    }
    
    /**
     * Get monthly revenue data for the last 6 months
     */
    private function getMonthlyRevenue()
    {
        $months = collect([]);
        $revenue = collect([]);
        
        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->startOfMonth()->subMonths($i);
            $months->push($date->format('M Y'));
            
            // Sum all payments for that month
            $monthlySum = UserSubscription::whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->sum('price');
            
            $revenue->push($monthlySum);
        }
        
        return [
            'labels' => $months,
            'data' => $revenue
        ];
    }
    
    /**
     * Get subscription plan distribution
     */
    private function getPlanDistribution()
    {
        $plans = SubPlan::where('status', 1)->get();
        $planNames = [];
        $planCounts = [];
        
        foreach ($plans as $plan) {
            $planNames[] = $plan->name;
            $planCounts[] = UserSubscription::where('subplan_id', $plan->id)
                            ->where('status', 'active')
                            ->count();
        }
        
        return [
            'labels' => $planNames,
            'data' => $planCounts
        ];
    }
    
    /**
     * Get weekly claims data
     */
    private function getWeeklyClaims()
    {
        $days = collect([]);
        $counts = collect([]);
        
        // Get last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days->push($date->format('D'));
            
            $count = Claim::whereDate('created_at', $date->toDateString())->count();
            $counts->push($count);
        }
        
        return [
            'labels' => $days,
            'data' => $counts
        ];
    }

    private function getRecentActivity()
    {
        $activity = [];
        
        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();
            
        foreach ($recentOrders as $order) {
            $customerName = $order->user ? $order->user->name : 'Guest';
            $activity[] = [
                'type' => 'order',
                'message' => "New order <strong>#$order->order_number</strong> placed by <strong>$customerName</strong> for <strong>" . Helpers::setCurrency($order->total) . "</strong>",
                'time' => $order->created_at->diffForHumans()
            ];
        }
        
        // Recent users
        $recentUsers = User::latest()
            ->take(3)
            ->get();
            
        foreach ($recentUsers as $user) {
            $activity[] = [
                'type' => 'user',
                'message' => "New user <strong>$user->name</strong> registered",
                'time' => $user->created_at->diffForHumans()
            ];
        }
        
        // Recent products
        $recentProducts = Product::latest()
            ->take(3)
            ->get();
            
        foreach ($recentProducts as $product) {
            $activity[] = [
                'type' => 'product',
                'message' => "New product <strong>$product->name</strong> added to inventory",
                'time' => $product->created_at->diffForHumans()
            ];
        }
        
        // Recent reviews
        $recentReviews = ProductReview::with(['product', 'user'])
            ->latest()
            ->take(3)
            ->get();
            
        foreach ($recentReviews as $review) {
            if ($review->product && $review->user) {
                $activity[] = [
                    'type' => 'review',
                    'message' => "<strong>{$review->user->name}</strong> reviewed <strong>{$review->product->name}</strong> with {$review->rating} stars",
                    'time' => $review->created_at->diffForHumans()
                ];
            }
        }
        
        // Sort by time (most recent first)
        usort($activity, function($a, $b) {
            return strtotime(Carbon::parse($b['time'])->toDateTimeString()) - strtotime(Carbon::parse($a['time'])->toDateTimeString());
        });
        
        return array_slice($activity, 0, 10);
    }

    public function generalsettings(Request $request)
    {
        $data = GeneralSetting::find(1);
        return view('admin.generalsettings', compact('data'));
    }

    public function generalsettingsupdate(Request $request)
    {
        $request->validate([
            'favicon' => 'mimes:jpeg,jpg,png,svg',
            'logo' => 'mimes:jpeg,jpg,png,svg',
        ]);

        $input = $request->all();
        $data = GeneralSetting::find(1);
        if ($file = $request->file('favicon')) {
            $name = time() . $file->getClientOriginalName();
            $name = str_replace(' ', '', $name);
            $file->move('assets/images/', $name);
            if ($data->favicon != null) {
                if (file_exists(public_path() . '/assets/images/' . $data->favicon)) {
                    unlink(public_path() . '/assets/images/' . $data->favicon);
                }
            }
            $data->favicon = $name;
        }
        if ($file = $request->file('logo')) {
            $name = time() . $file->getClientOriginalName();
            $name = str_replace(' ', '', $name);
            $file->move('assets/images/logo/', $name);
            if ($data->logo != null) {
                if (file_exists(public_path() . '/assets/images/logo/' . $data->logo)) {
                    unlink(public_path() . '/assets/images/logo/' . $data->logo);
                }
            }
            $data->logo = $name;
        }
        if ($file = $request->file('admin_logo')) {
            $name = time() . $file->getClientOriginalName();
            $name = str_replace(' ', '', $name);
            $file->move('assets/images/logo/', $name);
            if ($data->admin_logo != null) {
                if (file_exists(public_path() . '/assets/images/logo/' . $data->admin_logo)) {
                    unlink(public_path() . '/assets/images/logo/' . $data->admin_logo);
                }
            }
            $data->admin_logo = $name;
        }
        if ($file = $request->file('landing_page_img_1')) {
            $name = time() . $file->getClientOriginalName();
            $name = str_replace(' ', '', $name);
            $file->move('assets/images/', $name);
            if ($data->landing_page_img_1 != null) {
                if (file_exists(public_path() . '/assets/images/' . $data->landing_page_img_1)) {
                    unlink(public_path() . '/assets/images/' . $data->landing_page_img_1);
                }
            }
            $data->landing_page_img_1 = $name;
        }
        if ($file = $request->file('intro_video_cover')) {
            $name = time() . $file->getClientOriginalName();
            $name = str_replace(' ', '', $name);
            $file->move('assets/images/', $name);
            if ($data->intro_video_cover != null) {
                if (file_exists(public_path() . '/assets/images/' . $data->intro_video_cover)) {
                    unlink(public_path() . '/assets/images/' . $data->intro_video_cover);
                }
            }
            $data->intro_video_cover = $name;
        }
        if ($file = $request->file('intro_video')) {
            $name = time() . $file->getClientOriginalName();
            $name = str_replace(' ', '', $name);
            $file->move('assets/images/', $name);
            if ($data->intro_video != null) {
                if (file_exists(public_path() . '/assets/images/' . $data->intro_video)) {
                    unlink(public_path() . '/assets/images/' . $data->intro_video);
                }
            }
            $data->intro_video = $name;
        }

        $data->name = $request->name;
        $data->slogan = $request->slogan;
        $data->from_name = $request->name;
        $data->from_email = $request->from_email;
        
        $data->bit_value = $request->bit_value;
        $data->update();

        Session::flash('message', 'Successfully updated Data');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function passwordreset($value = '')
    {
        return view('admin.cpassword');
    }

    public function changepass(Request $request)
    {
        $request->validate([
            'cpass' => 'required',
            'newpass' => 'required',
            'renewpass' => 'required|same:newpass'
        ]);
        $admin = Auth::guard('admin')->user();
        if ($request->cpass) {
            if (Hash::check($request->cpass, $admin->password)) {
                $input['password'] = Hash::make($request->newpass);
            } else {
                Session::flash('message', 'Current password Does not match.');
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
            }
        }
        $admin->update($input);
        Session::flash('message', 'Successfully change your password');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function profile()
    {
        $data = Auth::guard('admin')->user();
        return view('admin.profile', compact('data'));
    }

    public function profileupdate(Request $request)
    {
        $request->validate([
            'photo' => 'mimes:jpeg,jpg,png,svg',
            'email' => 'unique:admins,email,' . Auth::guard('admin')->user()->id
        ]);

        $input = $request->all();
        $data = Auth::guard('admin')->user();

        if ($file = $request->file('photo')) {
            $data->photo = Helpers::update('admin/images/', $data->photo, config('fileformats.image'), $request->file('photo'));
        }

        $data->update($input);
        Session::flash('message', 'Data Updated Successfully !');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function social($value = '')
    {
        $data = SocialSetting::find(1);
        return view('admin.socialsettings', compact('data'));
    }

    public function socialupdate(Request $request)
    {
        $input = $request->all();
        $data = SocialSetting::find(1);

        $data->update($input);
        Session::flash('message', 'Data Updated Successfully !');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function livechat($id = null)
    {
        $messenger_color = Auth::user()->messenger_color;
        return view('admin.livechat.messenger', [
            'id' => $id ?? 0,
            'messengerColor' => $messenger_color ? $messenger_color : Chatify::getFallbackColor(),
            'dark_mode' => 'light',
        ]);
    }

    public function tawk($value = '')
    {
        return view('admin.livechat.index');
    }
}