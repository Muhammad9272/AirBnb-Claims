<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\ClaimComment;
use App\Models\ClaimEvidence;
use App\Models\ClaimStatusHistory;
use App\Models\InfluencerCommission;
use App\Models\GeneralSetting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\CentralLogics\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Services\NotificationService;

class ClaimController extends Controller
{
    /**
     * Display a listing of claims for the authenticated user
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $claims = Auth::user()->claims()->orderBy('created_at', 'desc')->paginate(10);
        return view('user.claims.index', compact('claims'));
    }

    /**
     * Show the form for creating a new claim
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Check if user can create a claim based on subscription
        $canCreateCheck = Auth::user()->canCreateClaim();
        
        if (!$canCreateCheck['can_create']) {
            return redirect()->route('user.claims.index')
                ->with('error', $canCreateCheck['message']);
        }
        
        return view('user.claims.create');
    }

    /**
     * Store a newly created claim
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'required|string',
            'property_address' => 'string|max:191',
            'amount_requested' => 'required|numeric|min:1',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after_or_equal:check_in_date',
            'incident_date' => 'required|date',
            'guest_name' => 'required|string|max:191',
            'guest_email' => 'nullable|email|max:191',
            'airbnb_reservation_code' => 'required|string|max:191',
            'evidence_files.*' => 'nullable|file|mimes:jpeg,jpg,png,gif,pdf,doc,docx|max:10240',
        ]);
        
        // Check if user can create a claim
        $canCreateCheck = Auth::user()->canCreateClaim();
        if (!$canCreateCheck['can_create']) {
            return redirect()->route('user.claims.index')
                ->with('error', $canCreateCheck['message']);
        }
        
        // Generate a unique claim number (year + random string)
        $claimNumber = date('Y') . '-' . strtoupper(Str::random(8));
        
        // Create the claim
        $claim = new Claim();
        $claim->user_id = Auth::id();
        $claim->claim_number = $claimNumber;
        $claim->title = $request->title;
        $claim->description = $request->description;
        $claim->property_address = $request->property_address;
        $claim->amount_requested = $request->amount_requested;
        
        // Guest information
        $claim->guest_name = $request->guest_name;
        $claim->guest_email = $request->guest_email;
        $claim->airbnb_reservation_code = $request->airbnb_reservation_code;
        
        // Parse dates to ensure proper formatting
        $claim->check_in_date = Carbon::parse($request->check_in_date);
        $claim->check_out_date = Carbon::parse($request->check_out_date);
        $claim->incident_date = Carbon::parse($request->incident_date);
        
        // Set initial status
        $claim->status = 'pending';
        $claim->save();
        
        // Add status history
        ClaimStatusHistory::create([
            'claim_id' => $claim->id,
            'to_status' => 'pending',
            'notes' => 'Claim submitted',
            'user_id' => Auth::id()
        ]);
        
        // Process evidence files
        if ($request->hasFile('evidence_files')) {
            foreach ($request->file('evidence_files') as $file) {
                // Use the Helper function instead of Laravel's storage
                $filename = Helpers::upload('claims/', config('fileformats.image'), $file);
                
                ClaimEvidence::create([
                    'claim_id' => $claim->id,
                    'file_path' => $filename,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),
                    'type' => 'other',
                    'uploaded_by' => Auth::id()
                ]);
            }
        }
        
        // Create influencer commission record (if applicable)
        $this->createInfluencerCommissionRecord($claim);
        
        // Notify admin about new claim
        $title = "New Claim Submitted";
        $message = "A new claim #{$claim->claim_number} has been submitted by {$claim->user->name}";
        $link = route('admin.claims.show', $claim->id);
        NotificationService::notifyAdmin('claim_submitted', $title, $message, $link);
        
        return redirect()->route('user.claims.show', $claim->id)
            ->with('success', 'Claim submitted successfully! Your claim number is ' . $claimNumber);
    }

    /**
     * Create influencer commission record when claim is created
     * Commission will be in 'pending' status until claim is approved
     *
     * @param  Claim  $claim
     * @return void
     */

    /**
     * Create influencer commission record when claim is created
     * Commission will be in 'pending' status until claim is approved
     *
     * @param  Claim  $claim
     * @return void
     */
    private function createInfluencerCommissionRecord($claim)
    {
        try {
            // Get general settings
            $gs = GeneralSetting::first();
            
            if (!$gs || !$gs->influencer_commission_percentage) {
                return; // Influencer commission not enabled
            }

            // Check if user was referred by someone
            if (!$claim->user->referred_by) {
                return; // User was not referred
            }

            // Get the referrer (who referred this user)
            $referrer = User::find($claim->user->referred_by);
            
            // Check if referrer exists and is an influencer
            if (!$referrer || $referrer->role_type !== 'influencer') {
                return; // Referrer not found or not an influencer
            }

            // Check if claim creation is within commission duration from CLAIM CREATION DATE
            if ($gs->influencer_commission_duration_days) {
                $claimCreationDate = $claim->created_at;
                $commissionEndDate = $claimCreationDate->copy()->addDays($gs->influencer_commission_duration_days);
                $now = now();
                
                if ($now->greaterThan($commissionEndDate)) {
                    \Log::info('Commission not created - duration expired', [
                        'claim_id' => $claim->id,
                        'claim_created' => $claimCreationDate,
                        'commission_end_date' => $commissionEndDate,
                        'current_date' => $now
                    ]);
                    return; // Commission period expired
                }
            }

            // Check if max claims limit is reached (if set)
            if ($gs->influencer_max_claims) {
                $existingCommissions = InfluencerCommission::where('influencer_user_id', $referrer->id)
                    ->where('customer_user_id', $claim->user_id)
                    ->whereIn('status', ['pending', 'approved', 'paid']) // Count all valid commissions
                    ->count();
                
                if ($existingCommissions >= $gs->influencer_max_claims) {
                    \Log::info('Commission not created - max claims limit reached', [
                        'claim_id' => $claim->id,
                        'influencer_id' => $referrer->id,
                        'customer_id' => $claim->user_id,
                        'existing_commissions' => $existingCommissions,
                        'max_allowed' => $gs->influencer_max_claims
                    ]);
                    return; // Max claims limit reached
                }
            }

            // Calculate estimated commission amount (based on requested amount)
            $estimatedCommission = ($claim->amount_requested * $gs->influencer_commission_percentage) / 100;
            $estimatedCommission = round($estimatedCommission, 2);

            // Prepare notes
            $notes = "Commission created for claim #{$claim->claim_number}. ";
            $notes .= "Customer: {$claim->user->name} (ID: {$claim->user->id}). ";
            $notes .= "Influencer: {$referrer->name} (ID: {$referrer->id}). ";
            $notes .= "Estimated commission based on requested amount: $" . number_format($claim->amount_requested, 2) . ". ";
            $notes .= "Commission rate: {$gs->influencer_commission_percentage}%. ";
            $notes .= "Claim created: {$claim->created_at->format('Y-m-d H:i:s')}. ";
            if ($gs->influencer_commission_duration_days) {
                $commissionEndDate = $claim->created_at->copy()->addDays($gs->influencer_commission_duration_days);
                $notes .= "Commission valid until: {$commissionEndDate->format('Y-m-d H:i:s')}.";
            }

            // Create influencer commission record with 'pending' status
            InfluencerCommission::create([
                'influencer_user_id' => $referrer->id,
                'customer_user_id' => $claim->user_id,
                'claim_id' => $claim->id,
                'subscription_id' => null,
                'estimated_commission' => $estimatedCommission,
                'commission_amount' => null, // Will be set when claim is approved
                'status' => 'pending', // Pending until claim is approved
                'commission_date' => null, // Will be set when claim is approved
                'paid_date' => null,
                'payment_method' => null,
                'payment_reference' => null,
                'notes' => $notes,
                'created_at' => now(),
            ]);

            \Log::info('Influencer commission record created', [
                'claim_id' => $claim->id,
                'influencer_id' => $referrer->id,
                'customer_id' => $claim->user_id,
                'estimated_commission' => $estimatedCommission
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to create influencer commission record: ' . $e->getMessage(), [
                'claim_id' => $claim->id,
                'user_id' => $claim->user_id,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Display a specific claim
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $claim = Claim::with(['comments.user', 'evidence', 'statusHistory.user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        
        return view('user.claims.show', compact('claim'));
    }

    /**
     * Add a comment to a claim
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addComment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string'
        ]);
        
        $claim = Claim::where('user_id', Auth::id())->findOrFail($id);
        
        $comment = new ClaimComment();
        $comment->claim_id = $claim->id;
        $comment->user_id = Auth::id();
        $comment->comment = $request->content;
        $comment->save();
        
        // Send notification about new comment
        NotificationService::newComment($comment);
        
        return redirect()->back()->with('success', 'Comment added successfully');
    }

    /**
     * Add evidence to a claim
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addEvidence(Request $request, $id)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:jpeg,jpg,png,gif,pdf,doc,docx|max:10240',
        ]);
        
        $claim = Claim::where('user_id', Auth::id())->findOrFail($id);
        
        // Only allow adding evidence if the claim is not in final status
        if (in_array($claim->status, ['approved', 'rejected', 'paid'])) {
            return redirect()->back()->with('error', 'Cannot add evidence to a finalized claim');
        }
        
        // Fix for count error - check if files exist and are not null
        if ($request->hasFile('files')) {
            $filesCount = count($request->file('files'));
            
            foreach ($request->file('files') as $file) {
                // Use the Helper function instead of Laravel's storage
                $filename = Helpers::upload('claims/', config('fileformats.image'), $file);
                
                ClaimEvidence::create([
                    'claim_id' => $claim->id,
                    'file_path' => $filename,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),
                    'description' => $request->description ?? null,
                    'type' => 'other',
                    'uploaded_by' => Auth::id()
                ]);
            }
            
            // Add a comment about the new evidence - fix for count() error
            $comment = new ClaimComment();
            $comment->claim_id = $claim->id;
            $comment->user_id = Auth::id();
            $comment->comment = 'Added ' . $filesCount . ' new piece(s) of evidence';
            if ($request->description) {
                $comment->comment .= ': ' . $request->description;
            }
            $comment->save();
            
            // Notify admin about new evidence
            NotificationService::newEvidence($claim, $filesCount);
        } else {
            return redirect()->back()->with('error', 'No files were uploaded.');
        }
        
        return redirect()->back()->with('success', 'Evidence added successfully');
    }
}