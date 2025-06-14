<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Helpers;
use App\CentralLogics\UserAccess;
use App\Http\Controllers\Controller;
use App\Models\CareerEvent;
use App\Models\JobApplication;
use App\Models\JobApplicationStage;
use Auth;
use Illuminate\Http\Request;
class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function __construct(Request $request)
    {
        $this->middleware(['auth']);
        $this->request = $request;
        $this->fileformats = config('fileformats.image') . '|' . config('fileformats.document');
    }

    private function jobApplicationAccess()
    {
          $response=UserAccess::hasAccess(auth()->user(),'job_applications',27387283);
          $UserAccess=$response['access']?true:false;
          return $UserAccess;
    }
    public function index()
    {
        if (!$this->jobApplicationAccess()) {
           return redirect()->route('user.dashboard')->with('error', 'You do not have permission to access this page.');
        }

        $userdata = Auth::user();
        $jobApplications = JobApplication::active()
            ->where('user_id', $userdata->id)
            ->has('stages')
            ->with('stages')
            ->get();

        $totalStagesCompleted = JobApplicationStage::whereHas('jobApplication', function ($query) use ($userdata) {
                $query->where('user_id', $userdata->id);
            })
            ->whereNotIn('status', ['Await'])
            ->count();


        $totalJobsInitiated = 0;
        foreach ($jobApplications as $jobApplication) {
            $firstStage = $jobApplication->stages->first();
            
            if ($firstStage && $firstStage->status !== 'Await') {
                $totalJobsInitiated++;
            }
        }
        $totalJobsApplied = count($jobApplications);
        
        $jobstats = [
            'totalJobsInitiated' => $totalJobsInitiated,
            'totalJobsApplied' => $totalJobsApplied,
            'totalStagesCompleted' => $totalStagesCompleted,
            
        ];

        $latestevents=CareerEvent::active()->latest()->take(3)->get();
        return view('user.jobapplications.index',compact('userdata','jobApplications','latestevents','jobstats'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($application_id, $stage_id)
    {
        if (!$this->jobApplicationAccess()) {
           return redirect()->route('user.dashboard')->with('error', 'You do not have permission to access this page.');
        }
        // Retrieve the specific stage and its related application
        $stage = JobApplicationStage::where('job_application_id', $application_id)->find($stage_id);

        if (!$stage) {
            // Handle the case where the stage is not found
            return response()->json(['error' => 'Stage not found.'], 404);
        }

        // Check if the stage belongs to the authenticated user
        if ($stage->jobApplication->user_id !== auth()->user()->id) {
            // Handle the case where the stage does not belong to the authenticated user
            return response()->json(['error' => 'Unauthorized access to stage data.'], 403);
        }

        // Load the Blade partial with stage data and return it as HTML
        $html = view('user.jobapplications.partials.stage', compact('stage'))->render();

        return response()->json(['html' => $html]);
    }



    public function uploadDocument(Request $request)
    {
        if (!$this->jobApplicationAccess()) {
           return redirect()->route('user.dashboard')->with('error', 'You do not have permission to access this page.');
        }
        try {
            $stageId = $request->input('stage_id');

            // Retrieve the specific stage
            $stage = JobApplicationStage::find($stageId);

            if (!$stage) {
                // Handle the case where the stage is not found
                return back()->with('error', 'Stage not found.');
            }

            // Check if the stage belongs to the authenticated user
            if ($stage->jobApplication->user_id !== Auth::id()) {
                // Handle the case where the stage does not belong to the authenticated user
                return back()->with('error', 'Unauthorized access to stage data.');
            }
            if(!$request->file('user_docs')){
                return back()->with('error', 'Plese add files.');
            }
            

            // Initialize an array to store user docs
            $userDocs = [];

            // Check if there are existing user docs and decode them
            if ($stage->user_docs) {
                $userDocs = json_decode($stage->user_docs);
            }

            // Loop through and store the uploaded files
            foreach ($request->file('user_docs') as $file) {
                // Store the file in your desired location
                $userDocs[] = Helpers::upload('job/applicationsfiles/', $this->fileformats, $file);

                // You can associate this path with the specific stage or user as needed
                // For example, you can save the path in a database table related to the stage or user
            }
            $stage->user_docs=json_encode($userDocs);
            $stage->user_docs_required=false;
            $stage->update();


            // Redirect back with a success message
            return back()->with('success', 'Documents uploaded successfully!');
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the file upload
            return back()->with('error', 'An error occurred while uploading documents.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
