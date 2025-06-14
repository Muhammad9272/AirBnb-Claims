<?php

namespace App\CentralLogics;
use App\Models\CareerEvent;
use App\Models\Coupon;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\QuizBankManagement;
use App\Models\SubPlan;
use App\Models\User;
use Auth;
use Session; 
use DB;
class SalesAnalytics
{
	public static function getSalesGraphData($orders)
    {
        return $orders->selectRaw('DATE(created_at) as date, SUM(pay_amount) as salesAmount, COUNT(*) as ordersCount')
            ->groupBy('date')
            ->get();
    }

	public static function getTopSellingPackages($orders)
	{
	    $orders = $orders->pluck('id');
	    $topSellingPackages = OrderItem::select('item_id', DB::raw('COUNT(*) as total'), DB::raw('SUM(discounted_price) as gross_sales'))
	    ->whereIn('order_id', $orders)
	    ->where('item_type', 'subscription_plan')
	    ->groupBy('item_id')
	    ->orderByDesc('total')
	    ->limit(5) // Get the top 5 selling packages
	    ->get();

	    // Retrieve the package details using the item_id
	    $packageIds = $topSellingPackages->pluck('item_id');
	    $packages = SubPlan::whereIn('id', $packageIds)->get();

	    // Combine the package details with the total sales
	    $topSellingPackages = $topSellingPackages->map(function ($item) use ($packages) {
	        $package = $packages->where('id', $item->item_id)->first();
	        $item->package = $package;
	        return $item;
	    });
	    // Return the top selling packages with their details
	    return $topSellingPackages;
	}
   
    public static  function getTopBookingTutors($orders)
	{
	    $orders = $orders->pluck('id');

	    $topBookingTutors = OrderItem::select('item_id', DB::raw('COUNT(*) as total'), DB::raw('SUM(discounted_price) as gross_sales') )
	        ->whereIn('order_id', $orders)
	        ->where('item_type', 'interview')
	        ->groupBy('item_id')
	        ->orderByDesc('total')
	        ->limit(10) // Get the top 5 booking tutors
	        ->get();
	    // Retrieve the tutor details using the item_id
	    $tutorIds = $topBookingTutors->pluck('item_id');
	    $tutors = User::whereIn('id', $tutorIds)->get();

	    // Combine the tutor details with the total bookings
	    $topBookingTutors = $topBookingTutors->map(function ($item) use ($tutors) {
	        $tutor = $tutors->where('id', $item->item_id)->first();
	        $item->tutor = $tutor;
	        return $item;
	    });

	    return $topBookingTutors;
	}


	public static function getTopPayingCustomers($orders)
	{
	    $topPayingCustomers = $orders->select('user_id', DB::raw('SUM(pay_amount) as total'), DB::raw('COUNT(*) as ordersCount'))
	        ->groupBy('user_id')
	        ->orderByDesc('total')
	        ->limit(10) // Get the top 5 paying customers
	        ->get();

	    // Retrieve the customer details
	    $customerIds = $topPayingCustomers->pluck('user_id');
	    $customers = User::whereIn('id', $customerIds)->get();

	    // Combine the customer details with the total order amount and orders count
	    $topPayingCustomers = $topPayingCustomers->map(function ($customer) use ($customers) {
	        $customer->customer = $customers->where('id', $customer->user_id)->first();
	        return $customer;
	    });

	    // Sort the result by total in descending order
	    $topPayingCustomers = $topPayingCustomers->sortByDesc('total')->values();

	    return $topPayingCustomers;
	}

    public static function getSalesByPreferences($orders)
    {
        $customerIds = $orders->pluck('user_id')->unique();
        // Get the count of users with internship preference
        $internshipCount = User::whereIn('id', $customerIds)
            ->where('internshipgraduate', 'internship')
            ->count();

        // Get the count of users with graduate preference
        $graduateCount = User::whereIn('id', $customerIds)
            ->where('internshipgraduate', 'graduate')
            ->count();

        // Calculate the percentages
        $totalCustomers = $internshipCount + $graduateCount;
        $internshipPercentage = ($totalCustomers > 0) ? ($internshipCount / $totalCustomers) * 100 : 0;
        $graduatePercentage = ($totalCustomers > 0) ? ($graduateCount / $totalCustomers) * 100 : 0;
        
        return [
            'internship' => $internshipCount,
            'graduate' => $graduateCount,
            'internshipPercentage' => $internshipPercentage,
            'graduatePercentage' => $graduatePercentage,
        ];
    }

    public static function getSalesByCoupon($orders)
    {
        // Get the total number of orders
        $totalOrders = $orders->count();
        // Get the number of orders with a coupon
        $couponOrders = $orders->whereNotNull('coupon_code')->count();
        $withoutCouponOrders = $totalOrders - $couponOrders;
        // Calculate the percentages
        $couponOrdersPercentage =($totalOrders>0)?($couponOrders / $totalOrders) * 100:0;
        $withoutCouponOrdersPercentage = ($totalOrders>0)?($withoutCouponOrders / $totalOrders) * 100:0;

        return [
            'couponOrders' => $couponOrders,
            'withoutCouponOrders' => $withoutCouponOrders,
            'couponOrdersPercentage' => $couponOrdersPercentage,
            'withoutCouponOrdersPercentage' => $withoutCouponOrdersPercentage,
        ];
    }



}