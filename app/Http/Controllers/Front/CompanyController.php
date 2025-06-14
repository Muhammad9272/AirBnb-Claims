<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\QuizBank;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {   

           // $query = Company::active()->latest();
            $query = Company::active()->orderBy('name', 'asc');

            // search functionality
            $search = $this->request->input('search');
            if ($search) {
               $query->where(function($q) use($search) {
                    $q->where('name', 'LIKE', '%'.$search.'%')
                      ->orWhere('tags', 'LIKE', '%'.$search.'%')
                       ->orWhere('small_description', 'LIKE', '%'.$search.'%');
                });
            }
            $companies = $query->paginate(20);
            // Append the search query to the pagination links
            $companies->appends(['search' => $search]);
            return view('front.company.index', compact('companies', 'search'));
    }
    public function show($slug)
    {   
        $data = Company::where('slug', $slug)->active()->firstOrFail();
        $relatedcompanies = Company::active()->where('id', '<>', $data->id)
                             ->inRandomOrder()
                             ->limit(10)
                             ->get();
        $quizIds = json_decode($data->sample_question_ids, true);
        if (!is_null($quizIds)) {
            $samplequestion = QuizBank::active()->whereIn('id', $quizIds)->get();
        } else {
            $samplequestion = collect(); // Create an empty collection if $quizIds is null
        }                                     
        return view('front.company.show', compact('data', 'relatedcompanies','samplequestion'));
    }
}
