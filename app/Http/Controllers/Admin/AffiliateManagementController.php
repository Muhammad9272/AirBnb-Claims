<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreatorAffiliate;
use App\Models\CreatorAffiliateCommission;
use App\Models\Lead;
use App\Models\User;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AffiliateManagementController extends Controller
{
    // Lead Management
    public function leads()
    {
        $leads = Lead::latest()->paginate(20);
        return view('admin.affiliate.leads', compact('leads'));
    }

    public function deleteLead($id)
    {
        Lead::findOrFail($id)->delete();
        return back()->with('success', 'Lead deleted successfully');
    }

    // Creator Affiliate Management
    public function creators()
    {
        $creators = CreatorAffiliate::with('user')->latest()->paginate(20);
        return view('admin.affiliate.creators', compact('creators'));
    }

    public function createCreator()
    {
        $users = User::whereNotIn('id', CreatorAffiliate::pluck('user_id'))->get();
        return view('admin.affiliate.create-creator', compact('users'));
    }

    public function storeCreator(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:creator_affiliates,user_id',
        ]);

        $gs = GeneralSetting::first();
        
        CreatorAffiliate::create([
            'user_id' => $request->user_id,
            'affiliate_code' => 'CR' . strtoupper(Str::random(8)),
            'commission_percentage' => $gs->creator_affiliate_commission_percentage ?? 10,
            'commission_limit_days' => $gs->creator_commission_limit_days ?? 30,
            'commission_limit_claims' => $gs->creator_commission_limit_claims,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.affiliates.creators')->with('success', 'Creator affiliate created successfully');
    }

    public function editCreator($id)
    {
        $creator = CreatorAffiliate::with('user')->findOrFail($id);
        return view('admin.affiliate.edit-creator', compact('creator'));
    }

    public function updateCreator(Request $request, $id)
    {
        $creator = CreatorAffiliate::findOrFail($id);
        
        $creator->update([
            'commission_percentage' => $request->commission_percentage,
            'commission_limit_days' => $request->commission_limit_days,
            'commission_limit_claims' => $request->commission_limit_claims,
            'is_active' => $request->has('is_active'),
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.affiliates.creators')->with('success', 'Creator affiliate updated successfully');
    }

    public function deleteCreator($id)
    {
        CreatorAffiliate::findOrFail($id)->delete();
        return back()->with('success', 'Creator affiliate deleted successfully');
    }

    // Commission Management
    public function commissions()
    {
        $commissions = CreatorAffiliateCommission::with(['creatorAffiliate.user', 'claim'])
            ->latest()
            ->paginate(20);
        
        return view('admin.affiliate.commissions', compact('commissions'));
    }

    public function updateCommissionStatus(Request $request, $id)
    {
        $commission = CreatorAffiliateCommission::findOrFail($id);
        
        $commission->update([
            'status' => $request->status,
            'paid_at' => $request->status === 'paid' ? now() : null,
            'payment_reference' => $request->payment_reference,
            'notes' => $request->notes,
        ]);

        // Update creator totals
        if ($request->status === 'paid') {
            $creator = $commission->creatorAffiliate;
            $creator->pending_commissions -= $commission->amount;
            $creator->paid_commissions += $commission->amount;
            $creator->save();
        }

        return back()->with('success', 'Commission status updated successfully');
    }

    // Affiliate Settings
    public function settings()
    {
        $gs = GeneralSetting::first();
        return view('admin.affiliate.settings', compact('gs'));
    }

    public function updateSettings(Request $request)
    {
        $gs = GeneralSetting::first();
        
        $gs->update([
            'general_affiliate_discount_percentage' => $request->general_affiliate_discount_percentage ?? 50,
            'creator_affiliate_commission_percentage' => $request->creator_affiliate_commission_percentage ?? 10,
            'lead_popup_discount_percentage' => $request->lead_popup_discount_percentage ?? 20,
            'creator_commission_limit_days' => $request->creator_commission_limit_days ?? 30,
            'creator_commission_limit_claims' => $request->creator_commission_limit_claims,
            'enable_lead_popup' => $request->has('enable_lead_popup'),
            'enable_general_affiliate' => $request->has('enable_general_affiliate'),
            'enable_creator_affiliate' => $request->has('enable_creator_affiliate'),
        ]);

        return back()->with('success', 'Affiliate settings updated successfully');
    }
}
