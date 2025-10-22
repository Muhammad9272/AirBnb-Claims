<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\InfluencerCommission;
use App\Classes\GeniusMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class InfluencerController extends Controller
{
    /**
     * Secret login to influencer account (Super admin only)
     */
    public function secret($id)
    { 
        if(Auth::guard('admin')->user()->IsSuper()){
            Auth::guard('web')->logout();
            $influencer = User::where('id', $id)
                              ->where('role_type', 'influencer')
                              ->firstOrFail();
            Auth::guard('web')->login($influencer); 
            return redirect()->route('user.influencer.index');
        }
        
        abort(403, 'Unauthorized action.');
    }

    /**
     * Display listing of influencers
     */
    public function index(Request $request)
    {
        $query = User::where('role_type', 'influencer');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('affiliate_code', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $influencers = $query->orderBy('created_at', 'desc')
                           ->paginate(20);

        // Statistics
        $stats = [
            'total_influencers' => User::where('role_type', 'influencer')->count(),
            'active_influencers' => User::where('role_type', 'influencer')->where('status', 1)->count(),
            'inactive_influencers' => User::where('role_type', 'influencer')->where('status', 0)->count(),
        ];

        return view('admin.influencers.index', compact('influencers', 'stats'));
    }

    /**
     * Show form to create new influencer
     */
    public function create()
    {
        return view('admin.influencers.create');
    }

    /**
     * Store new influencer
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
        ]);

        try {
            // Generate random password
            $password = Str::random(12);

            // Generate unique affiliate code
            do {
                $affiliateCode = strtoupper(substr(uniqid(), -8));
            } while (User::where('affiliate_code', $affiliateCode)->exists());

            // Create influencer user
            $influencer = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($password),
                'role_id' => 2,
                'role_type' => 'influencer',
                'affiliate_code' => $affiliateCode,
                'status' => 1,
                'email_verified_at' => now(),
                'is_email_verified' => 1,
            ]);

            // Send credentials email
            try {
                $mailer = new GeniusMailer();
                $mailer->sendInfluencerCredentials($influencer, $password);
                
                $message = 'Influencer created successfully and credentials sent to their email.';
                $messageType = 'success';
            } catch (\Exception $e) {
                Log::error('Influencer credentials email failed: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                $message = 'Influencer created successfully, but email sending failed: ' . $e->getMessage() . '. Password: ' . $password;
                $messageType = 'warning';
            }

            return redirect()->route('admin.influencers.index')
                           ->with($messageType, $message);

        } catch (\Exception $e) {
            Log::error('Influencer creation failed: ' . $e->getMessage());
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to create influencer. Please try again.');
        }
    }

    /**
     * Show influencer details
     */
    public function show(User $influencer)
    {
        if ($influencer->role_type !== 'influencer') {
            abort(404);
        }

        // Get total referred users
        $totalReferrals = User::where('referred_by', $influencer->id)->count();

        // Get active subscriptions from referred users
        $activeSubscriptions = User::where('referred_by', $influencer->id)
            ->whereHas('userSubscriptions', function($q) {
                $q->where('status', 'active');
            })
            ->count();

        // Get commission statistics
        $totalCommissions = $influencer->influencerCommissions()->sum('commission_amount');
        $paidCommissions = $influencer->influencerCommissions()->where('status', 'paid')->sum('commission_amount');
        $approvedCommissions = $influencer->influencerCommissions()->where('status', 'approved')->sum('commission_amount');
        $pendingCommissions = $influencer->influencerCommissions()->where('status', 'pending')->sum('commission_amount');
        $rejectedCommissions = $influencer->influencerCommissions()->where('status', 'rejected')->sum('commission_amount');

        // Get monthly stats
        $monthlyReferrals = User::where('referred_by', $influencer->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $monthlyEarnings = $influencer->influencerCommissions()
            ->where('status', 'paid')
            ->whereMonth('paid_date', now()->month)
            ->whereYear('paid_date', now()->year)
            ->sum('commission_amount');

        // Calculate conversion rate (users who subscribed / total referred)
        $subscribedUsers = User::where('referred_by', $influencer->id)
            ->whereHas('userSubscriptions')
            ->count();
        $conversionRate = $totalReferrals > 0 
            ? round(($subscribedUsers / $totalReferrals) * 100, 1)
            : 0;

        $stats = [
            'total_referrals' => $totalReferrals,
            'active_subscriptions' => $activeSubscriptions,
            'total_earnings' => $totalCommissions,
            'paid_commissions' => $paidCommissions,
            'approved_commissions' => $approvedCommissions,
            'pending_commissions' => $pendingCommissions,
            'rejected_commissions' => $rejectedCommissions,
            'monthly_referrals' => $monthlyReferrals,
            'monthly_earnings' => $monthlyEarnings,
            'conversion_rate' => $conversionRate,
        ];

        // Get commission history with pagination
        $commissions = $influencer->influencerCommissions()
            ->with(['customer', 'claim'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.influencers.show', compact('influencer', 'stats', 'commissions'));
    }

    /**
     * Update influencer status
     */
    public function updateStatus(Request $request, $influencer)
    {
        try {
            $influencerUser = User::findOrFail($influencer);
            
            if ($influencerUser->role_type !== 'influencer') {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid influencer'
                ], 400);
            }

            // Get status from request body
            $status = $request->input('status');
            
            // Toggle if status not provided
            if ($status === null) {
                $status = $influencerUser->status ? 0 : 1;
            }
            
            $influencerUser->status = $status;
            $influencerUser->save();

            $statusText = $status ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "Influencer {$statusText} successfully",
                'status' => $status
            ]);
        } catch (\Exception $e) {
            Log::error('Toggle status error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete influencer
     */
    public function destroy(User $influencer)
    {
        try {
            if ($influencer->role_type !== 'influencer') {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid influencer'
                ], 400);
            }

            $influencer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Influencer deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete influencer error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete influencer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resend credentials email
     */
    public function resendCredentials(Request $request, User $influencer)
    {
        try {
            if ($influencer->role_type !== 'influencer') {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid influencer'
                ], 400);
            }

            // Generate new password
            $newPassword = Str::random(12);
            $influencer->update([
                'password' => Hash::make($newPassword)
            ]);

            // Send new credentials
            $mailer = new GeniusMailer();
            $mailer->sendInfluencerCredentials($influencer, $newPassword);

            return response()->json([
                'success' => true,
                'message' => 'New credentials sent successfully to ' . $influencer->email
            ]);

        } catch (\Exception $e) {
            Log::error('Resend credentials failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send credentials: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update commission status (approve/reject)
     */
    public function updateCommissionStatus(Request $request, InfluencerCommission $commission)
    {
        try {
            $request->validate([
                'status' => 'required|in:approved,rejected',
                'notes' => 'nullable|string',
            ]);

            $oldStatus = $commission->status;
            $commission->status = $request->status;
            
            if ($request->filled('notes')) {
                $commission->notes = $request->notes;
            }

            // If approving, set commission date
            if ($request->status === 'approved' && $oldStatus === 'pending') {
                $commission->commission_date = now();
            }

            $commission->save();

            return response()->json([
                'success' => true,
                'message' => 'Commission status updated to ' . $request->status . ' successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Update commission status error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update commission status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark commission as paid
     */
    public function markCommissionPaid(Request $request, InfluencerCommission $commission)
    {
        try {
            $request->validate([
                'payment_method' => 'required|string',
                'payment_reference' => 'required|string',
                'notes' => 'nullable|string',
            ]);

            // Can only mark approved commissions as paid
            if ($commission->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only approved commissions can be marked as paid'
                ], 400);
            }

            $commission->status = 'paid';
            $commission->paid_date = now();
            $commission->payment_method = $request->payment_method;
            $commission->payment_reference = $request->payment_reference;
            
            if ($request->filled('notes')) {
                $existingNotes = $commission->notes ?? '';
                $commission->notes = $existingNotes . "\n[Paid] " . $request->notes;
            }

            $commission->save();

            return response()->json([
                'success' => true,
                'message' => 'Commission marked as paid successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Mark commission paid error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark commission as paid: ' . $e->getMessage()
            ], 500);
        }
    }
}