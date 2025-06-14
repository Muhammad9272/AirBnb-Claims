<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Company;
use App\Models\JobListing;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Session;

class JobListingController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }

    public function Datatables(Request $request)
    {
        $company = null;
        $company_slug=$request->company_slug;
        if ($company_slug ) {
            // Find company based on slug
            $company = Company::where('slug', $company_slug)->firstOrFail();
        }

        $query = JobListing::active()
            ->when($company, function ($query, $company) {
                return $query->where('company_id', $company->id);
            });

        $query=$query->latest()->get();
        return DataTables::of($query)
                            
                           ->addColumn('company_id', function(JobListing $data) {
                                return 
                                '<div class="d-flex align-items-center gap-2">
                                    <img height="37" src="'.Helpers::image($data->company ? $data->company->logo : '', 'company/logo/').'" alt="...">
                                    <span>
                                         '.($data->company ? $data->company->name : '').'
                                    </span>
                                </div>';
                                
                            })
                            ->editColumn('created_at', function (JobListing $data) {
                                return $data->created_at->format('M d, Y');
                            })
                            ->editColumn('last_date', function (JobListing $data) {
                                $lastDate = $data->last_date ? Carbon::parse($data->last_date)->format('M d, Y') : '';
                                return $lastDate;
                            })


                            ->addColumn('action', function(JobListing $data) {
                                
                                return '<div class="d-flex align-items-center  justify-content-end justify-content-xl-center gap-3">
                                    <a class="fs-20" target="_blank" href="'.$data->job_link.'"><i class="bx bx-link-alt"></i></a>
                                    <a class="fs-20"
                                        href="'.route('user.company.show', $data->company->slug) .'"><i class="bx bx-search-alt"></i> </a>
                                </div>';
                               
                            }) 
                            ->rawColumns(['company_id','action'])
                            ->toJson(); //--- Returning Json Data To Client Side


    }
    public function index(Request $request, $company_slug = null)
    {   
        
        // Get all unique positions and assessment stages
        $uniq_locations = JobListing::active()
            ->whereNotNull('location')
            ->select('location')
            ->distinct()
            ->pluck('location');
        $uniq_programs = JobListing::active()
            ->whereNotNull('program')
            ->select('program')
            ->distinct()
            ->pluck('program');
        // $quizmanagements->appends(['search' => $search]);
        $banner=Banner::active()->where('for_section','section_quiz_practice')->first();
        return view('user.joblistings.index', compact('company_slug','uniq_locations','request','banner','uniq_programs'));
       
    }







}
