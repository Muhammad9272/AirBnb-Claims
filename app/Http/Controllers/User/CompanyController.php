<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Company;
use App\Models\QuizBank;
use Illuminate\Http\Request;

class CompanyController extends Controller
{   
    public function __construct(Request $request)
    {
        $this->middleware('auth');
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
            $banner=Banner::active()->where('for_section','section_employ_guide')->first();
            return view('user.company.index', compact('companies', 'search','banner'));
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
        return view('user.company.show', compact('data', 'relatedcompanies','samplequestion'));
    }

    public function search(Request $request)
    {   
        $query = $request->input('q');
        $relatedcompanies = Company::active()->where('name', 'LIKE', "%$query%")
                             ->orWhere('small_description', 'LIKE', "%$query%")
                             ->limit(10)
                             ->get();
        return view('user.company.partials.related-company', compact('relatedcompanies', 'query'));
    }

}
