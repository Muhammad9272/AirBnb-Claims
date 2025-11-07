<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SurveyOption;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    public function index()
    {
        $user = Auth::user() ?? null;
        if(!$user) {
            return redirect()->route('user.login');
        }
        if ($user && ($user->survey_completed) ) {
            return redirect()->intended(route('user.claims.index'));
        }

        $options = SurveyOption::all();
        return view('user.survey', compact('options'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'survey_option' => 'required|exists:survey_options,id'
        ]);

        $user = Auth::user();
        $user->survey_answer = $request->survey_option;
        $user->survey_completed = true;
        $user->save();

        return redirect()->intended(route('user.claims.index'))->with('success', 'Thank you for completing the survey!');
    }
}
