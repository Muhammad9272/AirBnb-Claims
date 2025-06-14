<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubPlan;
use Illuminate\Support\Facades\Mail;
// use App\Mail\ContactFormSubmission; // Uncomment when implementing mail

class PageController extends Controller
{
    // Existing methods...
    
    /**
     * Display the About Us page.
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        $title = 'About Us';
        return view('front.about', compact('title'));
    }

    /**
     * Display the How It Works page.
     *
     * @return \Illuminate\View\View
     */
    public function howItWorks()
    {
        $title = 'How It Works';
        return view('front.how-it-works', compact('title'));
    }

    /**
     * Display the Pricing page.
     *
     * @return \Illuminate\View\View
     */
    public function pricing()
    {
        $title = 'Pricing';
        $plans = \App\Models\SubPlan::where('status', 1)->orderBy('price')->get();
        return view('front.pricing', compact('title', 'plans'));
    }

    /**
     * Display the Contact Us page.
     *
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        $title = 'Contact Us';
        return view('front.contact', compact('title'));
    }

    /**
     * Process the contact form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitContactForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        // TODO: Send email notification
        // Mail::to('info@airbnbclaims.com')->send(new ContactFormSubmission($validated));
        
        return back()->with('success', 'Thank you for your message! We will get back to you shortly.');
    }
}
