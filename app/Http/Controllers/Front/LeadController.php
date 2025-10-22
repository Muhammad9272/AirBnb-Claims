<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\GeneralSetting;
use App\Classes\GeniusMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    /**
     * Store lead from popup form
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'source' => 'required|string|max:255',
            'referral_name' => 'nullable|string|max:255|required_if:source,referral',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please fill in all required fields correctly.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $gs = GeneralSetting::first();

            // Check if email already exists in leads
            $existingLead = Lead::where('email', $request->email)->first();

            if ($existingLead) {
                // Email already exists, return error
                return response()->json([
                    'success' => false,
                    'message' => 'This email has already been registered. Please use a different email address.',
                ], 422);
            }

            // Create new lead
            $lead = Lead::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'source' => $request->source,
                'referral_name' => $request->referral_name,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'discount_code_used' => false,
                'is_registered' => false,
                'status' => 'new',
                'created_at' => now(),
            ]);

            // Get discount code from settings
            $discountCode = $gs->lead_discount_code ?? 'WELCOME20';
            $discountPercentage = $gs->lead_discount_percentage ?? 20;

            // Log the lead capture
            Log::info('New lead captured', [
                'lead_id' => $lead->id,
                'email' => $lead->email,
                'name' => $lead->name,
            ]);

            // Optional: Send email with discount code
            $mailer = new GeniusMailer();
            $mailer->sendLeadDiscountEmail($lead, $discountCode, $discountPercentage);

            return response()->json([
                'success' => true,
                'message' => 'Thank you! Check your email for the discount code.',
                'discount_code' => $discountCode,
                'discount_percentage' => $discountPercentage,
            ]);

        } catch (\Exception $e) {
            Log::error('Lead capture error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.',
            ], 500);
        }
    }

 
}