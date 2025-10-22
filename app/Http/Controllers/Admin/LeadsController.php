<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadsController extends Controller
{
    /**
     * Display a listing of leads
     */
    public function index(Request $request)
    {
        $query = Lead::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Source filter
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        // Registration status filter
        if ($request->filled('is_registered')) {
            if ($request->is_registered === '1') {
                // Has registered user
                $query->whereHas('registeredUser');
            } else {
                // No registered user
                $query->whereDoesntHave('registeredUser');
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $leads = $query->latest()
                      ->paginate(20)
                      ->appends($request->query());

        // Calculate statistics
        $stats = $this->getStatistics();

        return view('admin.leads.index', compact('leads', 'stats'));
    }

    /**
     * Get lead statistics
     */
    private function getStatistics()
    {
        return [
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'contacted_leads' => Lead::where('status', 'contacted')->count(),
            'converted_leads' => Lead::where('status', 'converted')->count(),
            
            // Count leads that have registered users (based on email match)
            'registered_leads' => Lead::whereHas('registeredUser')->count(),
            
            // Count leads where user used discount (based on email match and subscription)
            'discount_used_leads' => Lead::whereHas('registeredUser', function($query) {
                $query->whereHas('userSubscriptions', function($sq) {
                    $sq->whereNotNull('discount_code');
                });
            })->count(),
        ];
    }

    /**
     * Update lead status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,converted'
        ]);

        $lead = Lead::findOrFail($id);
        $lead->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Lead status updated successfully'
        ]);
    }

    /**
     * Export leads to CSV
     */
    public function export(Request $request)
    {
        $query = Lead::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_registered')) {
            if ($request->is_registered === '1') {
                $query->whereHas('registeredUser');
            } else {
                $query->whereDoesntHave('registeredUser');
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $leads = $query->latest()->get();

        $filename = 'leads_export_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($leads) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Phone',
                'Status',
                'Registered',
                'Registered User ID',
                'Registered User Name',
                'Discount Used',
                'Discount Code',
                'Discount Amount',
                'IP Address',
                'Created At'
            ]);

            // Data rows
            foreach ($leads as $lead) {
                $registeredUser = User::where('email', $lead->email)->first();
                $subscription = null;
                
                if ($registeredUser) {
                    $subscription = UserSubscription::where('user_id', $registeredUser->id)
                        ->whereNotNull('discount_code')
                        ->first();
                }

                fputcsv($file, [
                    $lead->id,
                    $lead->name,
                    $lead->email,
                    $lead->phone ?? 'N/A',
                    ucfirst($lead->status),
                    $registeredUser ? 'Yes' : 'No',
                    $registeredUser ? $registeredUser->id : 'N/A',
                    $registeredUser ? $registeredUser->name : 'N/A',
                    $subscription ? 'Yes' : 'No',
                    $subscription ? $subscription->discount_code : 'N/A',
                    $subscription ? $subscription->discount_amount : 'N/A',
                    $lead->ip_address ?? 'N/A',
                    $lead->created_at ? $lead->created_at->format('Y-m-d H:i:s') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete a lead
     */
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead deleted successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Lead deleted successfully');
    }
}