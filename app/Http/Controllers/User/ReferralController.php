<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ReferralTransaction;
use App\Models\WalletTransaction;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{
    /**
     * Display refer and earn page
     * Shows ALL users who signed up via referral link (whether they purchased or not)
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get ALL users who were referred (signed up via this user's link)
        // Not just those who have referral_transactions
        $allReferredUsers = User::where('referred_by', $user->id)
            ->select('id', 'name', 'email', 'created_at')
            ->get();
        
        // Check if each user has purchased a subscription
        $referralsData = $allReferredUsers->map(function($referredUser) {
            // Check if user has any active/completed subscription
            $hasPurchased = UserSubscription::where('user_id', $referredUser->id)
                ->whereIn('status', ['active', 'completed', 'cancelled'])
                ->exists();
            
            // Get credit amount from referral_transactions if purchased
            $referralTransaction = ReferralTransaction::where('referrer_user_id', auth()->id())
                ->where('referee_user_id', $referredUser->id)
                ->where('status', 'completed')
                ->first();
            
            return (object)[
                'id' => $referredUser->id,
                'name' => $referredUser->name,
                'email' => $referredUser->email,
                'created_at' => $referredUser->created_at,
                'has_purchased' => $hasPurchased,
                'credit_amount' => $referralTransaction ? $referralTransaction->credit_amount : 0,
            ];
        });
        
        // Paginate manually
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $referrals = new \Illuminate\Pagination\LengthAwarePaginator(
            $referralsData->forPage($currentPage, $perPage),
            $referralsData->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        // Calculate statistics
        $totalSignups = $allReferredUsers->count();
        $purchasedCount = $referralsData->where('has_purchased', true)->count();
        $notPurchasedCount = $referralsData->where('has_purchased', false)->count();
        
        // Total earned from completed referral transactions
        $totalEarned = ReferralTransaction::where('referrer_user_id', $user->id)
            ->where('status', 'completed')
            ->sum('credit_amount');
        
        return view('user.affiliate.index', compact(
            'referrals',
            'totalSignups',
            'purchasedCount',
            'notPurchasedCount',
            'totalEarned'
        ));
    }

    /**
     * Display wallet transactions
     *
     * @return \Illuminate\View\View
     */
    public function wallet()
    {
        $user = auth()->user();
        
        // Get wallet transactions with pagination
        $transactions = $user->walletTransactions()
            ->with(['relatedUser', 'relatedSubscription'])
            ->latest()
            ->paginate(15);
        
        // Get statistics
        $totalEarned = $user->walletTransactions()
            ->where('transaction_type', 'referral_earned')
            ->sum('amount');
            
        $totalSpent = abs($user->walletTransactions()
            ->where('transaction_type', 'subscription_used')
            ->sum('amount'));
        
        return view('user.affiliate.wallet', compact(
            'transactions',
            'totalEarned',
            'totalSpent'
        ));
    }
}