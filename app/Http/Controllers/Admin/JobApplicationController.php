<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Validator;

class JobApplicationController extends Controller
{

    public function __construct(){
     $this->middleware('auth:admin');
     $this->fileformats=config('fileformats.image') . '|' . config('fileformats.document');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatables()
    {   
        $datas = JobApplication::with(['company', 'job'])->orderBy('id', 'desc')->get();  

        return DataTables::of($datas)
            ->addIndexColumn()
            ->addColumn('user', function (JobApplication $data) {
                return $data->user ? $data->user->affiliate_code . '-' . $data->user->email : '';
            })
            ->addColumn('jobs_applied', function (JobApplication $data) {
                return $data->jobs_applied;
            })
            ->addColumn('company', function (JobApplication $data) {
                return $data->company ? $data->company->name : '';
            })
            ->addColumn('jobtitle', function (JobApplication $data) {
                $lastDate = $data->last_date ? Carbon::parse($data->last_date)->format('M d, Y') : '';
                return '<div>
                            <p>' . ($data->job ? $data->job->title : '') . '
                            <small class="d-block"><span class="fw-bold">Created At : </span>' . $data->created_at->format('M d, Y') . '</small>
                            
                            
                             <small class="d-block"><span class="fw-bold">Location : </span>' . $data->location . '</small>
                             </p>
                        </div>';
            })
            ->addColumn('status', function(JobApplication $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.job.applications.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.job.applications.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
            })
            ->addColumn('action', function(JobApplication $data) {
                return '<div class="action-list d-flex gap-1">
                    <a href="' . route('admin.job.applications.edit', $data->id) . '" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> 
                    <a href="' . route('admin.job.applications.duplicate', $data->id) . '" class="btn btn-success btn-sm fs-13 waves-effect waves-light"><i class="ri-copy-fill align-middle fs-16 me-2"></i>Duplicate</a>
                    <a data-href="' . route('admin.job.applications.delete', $data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete</a>
                </div>';
            })

            ->rawColumns(['jobtitle', 'status', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }


    public function index()
    {
        return view('admin.jobs.jobapplications.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $discompanies = JobListing::distinct('company_id')->pluck('company_id');
        $companies = Company::whereIn('id', $discompanies)->get();

        $svipusers=User::user()->active()->whereHas('activeuserSubscriptions', function ($query) {
            $query->where('subplan_id', '=', 4); // Replace 4 with the ID of the SVIP Membership plan.
        })->get();
        return view('admin.jobs.jobapplications.create',compact('companies','svipusers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
         // dd($request->all());
        // validate the form data
        $rules=[
            'user_id' => 'required|exists:users,id',
            'jobs_applied' => 'nullable|string',
            'company_id' => 'required|exists:companies,id',
            'service_line' => 'required|string',
            'location' => 'required|string',
            'job_id' => 'required|exists:job_listings,id',
            // 'instruction_form' => 'nullable|string',
            // 'resume' => 'nullable|string',
            // 'motivation_letter' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        try{

            $jobApplication = new JobApplication();
            $input = $request->all();

            // Fill and save the main job application data
            $jobApplication->fill([
                'user_id' => $input['user_id'],
                'jobs_applied' => $input['jobs_applied'],
                'company_id' => $input['company_id'],
                'service_line' => $input['service_line'],
                'location' => $input['location'],
                'job_id' => $input['job_id'],
            ])->save();

            // Handle file uploads using the Helpers::upload function
            $jobApplication->instruction_form = Helpers::upload('job/applicationsfiles/', $this->fileformats, $request->file('instruction_form'));
            $jobApplication->resume = Helpers::upload('job/applicationsfiles/', $this->fileformats , $request->file('resume'));
            $jobApplication->motivation_letter = Helpers::upload('job/applicationsfiles/', $this->fileformats, $request->file('motivation_letter'));

            $jobApplication->save();
            // Handle stages
            $stageCount = count($input['stage_name']);
            for ($i = 0; $i < $stageCount; $i++) {
                $adminDocs = [];
                // Upload admin documents for the current stage
                if ($request->hasFile("admin_docs.$i")) {
                    foreach ($request->file("admin_docs.$i") as $file) {
                        $adminDocs[] = Helpers::upload('job/applicationsfiles/', $this->fileformats, $file);
                    }
                }

                // Create the stage with the uploaded admin documents
                $jobApplication->stages()->create([
                    'stage_name' => $input['stage_name'][$i] ?? null,
                    'status' => $input['status'][$i] ?? 'pending',
                    'last_date' => $input['last_date'][$i] ?? null,
                    'details' => $input['details'][$i] ?? null,
                    'admin_docs' => json_encode($adminDocs),
                    'user_docs_required' => $request->input("user_docs_required.$i", false),
                ]);
            }

            // Session::flash('message', 'Data Added Successfully !');
            // Session::flash('alert-class', 'alert-success');
            return response()->json([
                'success' => true,
                'msg' => "Data has been saved successfully!",
                'route'=>route('admin.job.applications.create'),
            ]);

        } catch (\Exception $e) {
            // Log the exception for debugging
            //\Log::error('Error in store method: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while processing your request. Please try again later.'.$e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $data=JobApplication::find($id);
        $discompanies = JobListing::distinct('company_id')->pluck('company_id');
        $companies = Company::whereIn('id', $discompanies)->get();

        $serviceLines = JobListing::where('company_id', $data->company_id)->distinct('service_line')->pluck('service_line');
        $locations = JobListing::where('company_id', $data->company_id)
                        ->where('service_line', $data->service_line)
                        ->distinct('location')
                        ->pluck('location');
        $jobtitles = JobListing::where('company_id', $data->company_id)
                        ->where('service_line', $data->service_line)
                        ->where('location', $data->location)
                        ->get(['id', 'title']);


        $svipusers=User::user()->active()->whereHas('activeuserSubscriptions', function ($query) {
            $query->where('subplan_id', '=', 4); // Replace 4 with the ID of the SVIP Membership plan.
        })->get();

        $stagesData = $data->stages; // Assuming stages is the relationship method in JobApplication model.
        return view('admin.jobs.jobapplications.edit', compact('data', 'companies', 'svipusers', 'serviceLines', 'locations', 'jobtitles', 'stagesData'));
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {

        // validate the form data
        $rules = [
            'user_id' => 'required|exists:users,id',
            'jobs_applied' => 'nullable|string',
            'company_id' => 'required|exists:companies,id',
            'service_line' => 'required|string',
            'location' => 'required|string',
            'job_id' => 'required|exists:job_listings,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        try {
            $jobApplication = JobApplication::find($id);
            $input = $request->all();
            if (preg_match("/Copy/i", $jobApplication->jobs_applied) && !preg_match("/Copy/i", $input['jobs_applied'])) {
                $jobApplication->status = 1;
            }

            // Update the main job application data
            $jobApplication->update([
                'user_id' => $input['user_id'],
                'jobs_applied' => $input['jobs_applied'],
                'company_id' => $input['company_id'],
                'service_line' => $input['service_line'],
                'location' => $input['location'],
                'job_id' => $input['job_id'],
            ]);

            // Handle file uploads using the Helpers::upload function if new files are provided
            if ($request->hasFile('instruction_form')) {
                $jobApplication->instruction_form = Helpers::update('job/applicationsfiles/',$jobApplication->instruction_form, $this->fileformats, $request->file('instruction_form'));
            }

            if ($request->hasFile('resume')) {
                $jobApplication->resume = Helpers::update('job/applicationsfiles/',$jobApplication->resume, $this->fileformats, $request->file('resume'));
            }

            if ($request->hasFile('motivation_letter')) {
                $jobApplication->motivation_letter = Helpers::update('job/applicationsfiles/',$jobApplication->motivation_letter, $this->fileformats, $request->file('motivation_letter'));
            }
           

            $jobApplication->save();

            // Handle stages
            $stageCount = count($input['stage_name']);

            $existingStageIds = $jobApplication->stages->pluck('id')->toArray();

            for ($i = 0; $i < $stageCount; $i++) {
                $adminDocs = [];
                
                // Upload admin documents for the current stage
                if ($request->hasFile("admin_docs.$i")) {
                    foreach ($request->file("admin_docs.$i") as $file) {
                        $adminDocs[] = Helpers::upload('job/applicationsfiles/', $this->fileformats, $file);
                    }
                } elseif (isset($input['stage_id'][$i])) {
                    // If no new files and the stage already exists, retain existing admin_docs
                    $stage = $jobApplication->stages()->find($input['stage_id'][$i]);
                    $adminDocs = json_decode($stage->admin_docs, true);
                }

                // Create or update the stage with the uploaded admin documents
                if(isset($input['stage_id'][$i])){
                     $stage = $jobApplication->stages()->find(($input['stage_id'][$i]));
                 }else{
                    $stage = $jobApplication->stages()->make();
                }
               
                $stage->fill([
                    'stage_name' => $input['stage_name'][$i] ?? null,
                    'status' => $input['status'][$i] ?? 'Pending',
                    'last_date' => $input['last_date'][$i] ?? null,
                    'details' => $input['details'][$i] ?? null,
                    'admin_docs' => json_encode($adminDocs),
                    'user_docs_required' => $request->input("user_docs_required.$i", false),
                ])->save();
            }

            // Identify and delete stages that are no longer present in the request
            $stagesToRemove = array_diff($existingStageIds, $input['stage_id'] ?? []);
            $jobApplication->stages()->whereIn('id', $stagesToRemove)->delete();

            return response()->json([
                'success' => true,
                'msg' => 'Data has been updated successfully!',
                'route' => route('admin.job.applications.edit', $id),
            ]);

        } catch (\Exception $e) {
            // Log the exception for debugging
            // \Log::error('Error in update method: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while processing your request. Please try again later.' . $e->getMessage(),
            ]);
        }
    }


        //*** GET Request
    public function status($id1,$id2)
    {
        $data = JobApplication::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        // try {
            // Find the Job Application
            $jobApplication = JobApplication::findOrFail($id);

            // Delete related records (assuming you have relationships defined)
            $jobApplication->stages()->delete(); // Adjust this based on your actual relationships

            // Delete the Job Application
            $jobApplication->delete();

            return response()->json([
                'success' => true,
                'msg' => 'Job Application and related records have been deleted successfully!',
            ]);

        // } catch (\Exception $e) {
        //     // Log the exception for debugging
        //     // \Log::error('Error in destroy method: ' . $e->getMessage());

        //     return response()->json([
        //         'error' => 'An error occurred while processing your request. Please try again later.',
        //     ]);
        // }
    }

    public function duplicateJobApplication($id)
    {
        // Find the original job application record
        $originalJobApplication = JobApplication::findOrFail($id);

        // Create the initial duplicated name with the '(Copy)' suffix
        $duplicatedName = $originalJobApplication->jobs_applied . ' (Copy)';
        // Check if the name is already used, and increment the copy count until a unique name is found
        $copyCount = 1;
        while (JobApplication::where('jobs_applied', $duplicatedName)->exists()) {
            $duplicatedName = $originalJobApplication->jobs_applied . ' (Copy ' . $copyCount . ')';
            $copyCount++;
        }

        // Duplicate the job application record
        $duplicatedJobApplication = $originalJobApplication->replicate();
        $duplicatedJobApplication->jobs_applied = $duplicatedName; // Modify the name to indicate it's a copy

         
        // Check if the instruction_form file exists and duplicate it
        if ($originalJobApplication->instruction_form) {
            $duplicatedJobApplication->instruction_form = Helpers::duplicateFile($originalJobApplication->instruction_form, 'job/applicationsfiles/');
        }

        // Check if the resume file exists and duplicate it
        if ($originalJobApplication->resume) {
            $duplicatedJobApplication->resume = Helpers::duplicateFile($originalJobApplication->resume, 'job/applicationsfiles/');
        }

        // Check if the motivation_letter file exists and duplicate it
        if ($originalJobApplication->motivation_letter) {
            $duplicatedJobApplication->motivation_letter = Helpers::duplicateFile($originalJobApplication->motivation_letter, 'job/applicationsfiles/');
        }
        
        $duplicatedJobApplication->status=0;
        $duplicatedJobApplication->save();

        // Create an empty stage for the duplicated job application
        $emptyStage = $duplicatedJobApplication->stages()->make([
            'stage_name' => 'New Stage', // Modify this as needed
            'status' => 'Await', // Modify this as needed
            'last_date' => null, // Modify this as needed
            'details' => null, // Modify this as needed
            'admin_docs' => json_encode([]), // Set an empty array for admin_docs
            'user_docs_required' => 0, // Modify this as needed
        ]);
        $emptyStage->save();


        // Redirect to the edit page of the duplicated job application
        return redirect()->route('admin.job.applications.edit', $duplicatedJobApplication->id)->with('success', 'Job Application Duplicated successfully');
    }



    public function getServiceLines(Request $request)
    {
        $company_id = $request->input('company_id');
        $serviceLines = JobListing::where('company_id', $company_id)->distinct('service_line')->pluck('service_line');
        
        return response()->json($serviceLines);
    }

    public function getLocations(Request $request)
    {
        $company_id = $request->input('company_id');
        $service_line = $request->input('service_line');

        $locations = JobListing::where('company_id', $company_id)
                        ->where('service_line', $service_line)
                        ->distinct('location')
                        ->pluck('location');

        return response()->json($locations);
    }

    public function getJobTitles(Request $request)
    {
        $company_id = $request->input('company_id');
        $service_line = $request->input('service_line');
        $location = $request->input('location');

        $jobs = JobListing::where('company_id', $company_id)
                        ->where('service_line', $service_line)
                        ->where('location', $location)
                        ->get(['id', 'title']);

        $jobTitles = $jobs->pluck('title', 'id');

        return response()->json($jobTitles);
    }
}
