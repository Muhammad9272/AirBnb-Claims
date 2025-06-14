<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index()
    {
        return view('front.help.overview');
    }

    public function faqs()
    {
        return view('front.help.faqs');
    }

    public function guides()
    {
        return view('front.help.guides');
    }

    public function terms()
    {
        return view('front.help.terms');
    }

    public function privacy()
    {
        return view('front.help.privacy');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        // Implement search logic here based on your project's needs
        
        return view('front.help.search', compact('query'));
    }
}
