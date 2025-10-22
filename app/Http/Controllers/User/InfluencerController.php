<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InfluencerController extends Controller
{
    /**
     * Display influencer dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Check if user is an influencer
        if ($user->role_type !== 'influencer') {
            return redirect()->route('user.dashboard')
                           ->with('error', 'Access denied. This section is only for influencers.');
        }

        // Get total users referred by this influencer
        $totalReferrals = User::where('referred_by', $user->id)->count();

        // Get active subscriptions from referred users
        $activeSubscriptions = User::where('referred_by', $user->id)
            ->whereHas('userSubscriptions', function($q) {
                $q->where('status', 'active');
            })
            ->count();

        // Get commission statistics
        $totalEarnings = $user->influencerCommissions()
            ->where('status', 'paid')
            ->sum('commission_amount');
            
        $pendingEarnings = $user->influencerCommissions()
            ->whereIn('status', ['pending', 'approved'])
            ->sum('commission_amount');

        // Get this month's statistics
        $monthlyReferrals = User::where('referred_by', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $monthlyEarnings = $user->influencerCommissions()
            ->where('status', 'paid')
            ->whereMonth('paid_date', now()->month)
            ->whereYear('paid_date', now()->year)
            ->sum('commission_amount');

        // Get total commission count
        $totalCommissions = $user->influencerCommissions()->count();
        $paidCommissions = $user->influencerCommissions()
            ->where('status', 'paid')
            ->count();
        
        $approvedCommissions = $user->influencerCommissions()
            ->where('status', 'approved')
            ->count();

        // Calculate conversion rate (users with approved claims / total referred)
        $usersWithApprovedClaims = User::where('referred_by', $user->id)
            ->whereHas('claims', function($q) {
                $q->where('status', 'approved');
            })
            ->count();
        
        $conversionRate = $totalReferrals > 0 
            ? round(($usersWithApprovedClaims / $totalReferrals) * 100, 1)
            : 0;

        // Get commission history with pagination
        $commissions = $user->influencerCommissions()
            ->with(['customer', 'claim'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.influencers.index', compact(
            'totalReferrals',
            'activeSubscriptions',
            'totalEarnings',
            'pendingEarnings',
            'monthlyReferrals',
            'monthlyEarnings',
            'totalCommissions',
            'paidCommissions',
            'approvedCommissions',
            'conversionRate',
            'commissions'
        ));
    }
}
