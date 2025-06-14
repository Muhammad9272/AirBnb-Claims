<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\GeneralSetting;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\CentralLogics\Helpers;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $categories = ServiceCategory::active()->get();
        
        $query = Service::with('category')->active();
        
        // Filter by category if selected
        if ($request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Add search functionality
        if ($request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('summary', 'like', '%' . $searchTerm . '%')
                  ->orWhere('content', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('category', function($categoryQuery) use ($searchTerm) {
                      $categoryQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }
        
        $services = $query->latest()->paginate(9);
         // Retain search and category filters when paginating
        $services->appends($request->only(['search', 'category']));
        
        return view('front.services.index', compact('categories', 'services'));
    }

    public function show($slug)
    {
        $service = Service::with('category')
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Service::active()
            ->where('category_id', $service->category_id)
            ->where('id', '!=', $service->id)
            ->take(3)
            ->get();

        return view('front.services.show', compact('service', 'related'));
    }

    public function submitQuote(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'service_title' => 'required|string',
            'selected_package' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string',
        ]);

        $service = Service::findOrFail($request->service_id);
        $packages = json_decode($service->items, true);
        $selectedPackage = $packages[$request->selected_package] ?? null;

        if (!$selectedPackage) {
            return back()->with('error', 'Invalid service package selected.');
        }

        // Format email message
        $emailBody = "New Service Quote Request\n\n";
        $emailBody .= "Service: {$service->title}\n";
        $emailBody .= "Package: {$selectedPackage['label']}\n";
        $emailBody .= "Price: " . Helpers::setCurrency($selectedPackage['price']) . "\n\n";
        $emailBody .= "Customer Details:\n";
        $emailBody .= "Name: {$request->name}\n";
        $emailBody .= "Email: {$request->email}\n";
        $emailBody .= "Phone: {$request->phone}\n\n";
        
        if ($request->message) {
            $emailBody .= "Additional Message:\n{$request->message}\n";
        }

        $gs=GeneralSetting::findOrFail(1);
        try {
            $mailer = new GeniusMailer();
            $mailData = [
                'to' => $gs->from_email,
                'subject' => 'New Service Quote Request: ' . $service->title,
                'body' => $emailBody,
            ];

            $mailer->sendCustomMail($mailData);

            return redirect()->back()->with('success', 'Your quote request has been successfully submitted. We will contact you shortly.');
        } catch (\Exception $e) {
            \Log::error('Service Quote Email Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an error submitting your quote request. Please try again later.');
        }
    }
}
