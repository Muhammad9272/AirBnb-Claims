<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SurveyOption;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SurveyAnalyticsController extends Controller
{
    public function index()
    {
        // Get total users and responses
        $totalUsers = User::count();
        $totalResponses = User::whereNotNull('survey_answer')->count();
        $responseRate = $totalUsers > 0 ? ($totalResponses / $totalUsers) * 100 : 0;

        // Get survey data with response counts and proper date handling
        $surveyData = SurveyOption::select([
            'survey_options.*',
            DB::raw('COUNT(users.id) as responses_count'),
            DB::raw('MAX(users.updated_at) as last_response_date')
        ])
        ->leftJoin('users', 'users.survey_answer', '=', 'survey_options.id')
        ->groupBy([
            'survey_options.id',
            'survey_options.option_text',
            'survey_options.created_at',
            'survey_options.updated_at'
        ])
        ->orderBy('responses_count', 'desc')
        ->get()
        ->map(function ($item) {
            // Convert the last_response_date to Carbon instance if it exists
            $item->last_response = $item->last_response_date ? Carbon::parse($item->last_response_date) : null;
            return $item;
        });

        // Get time series data for the last 30 days with proper date handling
        $timeSeriesData = User::whereNotNull('survey_answer')
            ->where('updated_at', '>=', Carbon::now()->subDays(30))
            ->select([
                DB::raw('DATE(updated_at) as date'),
                DB::raw('COUNT(*) as responses')
            ])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'x' => Carbon::parse($item->date)->format('Y-m-d'),
                    'y' => (int)$item->responses
                ];
            })
            ->values()
            ->toArray();

        return view('admin.survey-analytics.index', compact(
            'totalResponses',
            'responseRate',
            'surveyData',
            'timeSeriesData'
        ));
    }
}
