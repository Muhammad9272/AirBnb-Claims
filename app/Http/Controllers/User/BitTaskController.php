<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BitTask;
use App\Models\BitSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use DataTables;
use App\CentralLogics\Helpers;
use Carbon\Carbon;

class BitTaskController extends Controller
{
     public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }
    public function index()
    {
        if ($this->request->ajax()) {
            $tasks = BitTask::where('status', 1)->orderBy('created_at', 'desc');
            
            // Get user's submissions from last 30 days, grouped by task
            $userSubmissions = BitSubmission::where('user_id', Auth::id())
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->select('bit_task_id', \DB::raw('count(*) as total'))
                ->groupBy('bit_task_id')
                ->get()
                ->keyBy('bit_task_id');

            return DataTables::of($tasks)
                ->addColumn('action', function($task) {
                    return '<a href="'.route('user.bit-tasks.show', $task->slug).'" class="btn-primary">View Task</a>';
                })
                ->addColumn('status', function($task) use ($userSubmissions) {
                    $status = '';
                    
                    // Check if user has any submissions for this task
                    if (isset($userSubmissions[$task->id]) && $userSubmissions[$task->id]->total > 0) {
                        $status .= '<span class="badge bg-success">Submitted</span>';
                    }
                    
                    // Check if user has reached their submission limit for this task
                    if ($task->max_submissions && 
                        isset($userSubmissions[$task->id]) && 
                        $userSubmissions[$task->id]->total >= $task->max_submissions) {
                        $status .= '<span class="badge bg-danger">Max Reached</span>';
                    }
                    
                    return $status;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('user.bit-tasks.index');
    }
    
    public function show($slug)
    {
        $task = BitTask::where('status', 1)
            ->where('slug', $slug)->firstOrFail();
        
        // Get all user submissions for this task
        $userSubmissions = BitSubmission::where('user_id', Auth::id())
            ->where('bit_task_id', $task->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get submissions from the last 30 days for max_submissions check
        $recentSubmissions = BitSubmission::where('user_id', Auth::id())
            ->where('bit_task_id', $task->id)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->get();
            
        // Count total submissions in the past 30 days (regardless of status)
        $recentSubmissionsCount = $recentSubmissions->count();
        
        // Check if user has any approved submission for this task
        $hasApprovedSubmission = $userSubmissions->where('status', 'approved')->count() > 0;
            
        // Check if user has a pending submission
        $hasPendingSubmission = $userSubmissions->where('status', 'pending')->count() > 0;
        
        // Get latest submission to display to user
        $latestSubmission = $userSubmissions->first(); // Already sorted by desc
        
        return view('user.bit-tasks.show', compact(
            'task', 
            'userSubmissions',
            'latestSubmission', 
            'recentSubmissionsCount',
            'hasApprovedSubmission',
            'hasPendingSubmission'
        ));
    }
    
    public function submit(Request $request, $slug)
    {
        $task = BitTask::where('status', 1)
            ->where('slug', $slug)->firstOrFail();
        
        // Validate request with clearer error messages
        $rules = [
            'submission_content' => 'required|string|min:10',
        ];
        
        if ($task->required_proof) {
            $rules['proof'] = [
                'required',
                'array',
                'min:1',
                'max:'.config('fileformats.max_proof_images')
            ];
            $rules['proof.*'] = [
                'required',
                'file',
                'mimes:jpeg,png,jpg,pdf',
                'max:2048'
            ];
        }
        
        $messages = [
            'proof.max' => 'You can upload maximum '.config('fileformats.max_proof_images').' files.',
            'proof.*.max' => 'Each file must not exceed 2MB.',
            'proof.*.mimes' => 'Only JPEG, PNG and PDF files are allowed.'
        ];
        
        $request->validate($rules, $messages);
        
        // Check if user already has a pending submission for this task
        $pendingSubmission = BitSubmission::where('user_id', Auth::id())
            ->where('bit_task_id', $task->id)
            ->where('status', 'pending')
            ->exists();
            
        if ($pendingSubmission) {
            return redirect()->back()->with('error', 'You already have a pending submission for this task. Please wait for review.');
        }
        
        // Check if user already has an approved submission for this task
        $approvedSubmission = BitSubmission::where('user_id', Auth::id())
            ->where('bit_task_id', $task->id)
            ->where('status', 'approved')
            ->exists();
            
        if ($approvedSubmission) {
            return redirect()->back()->with('error', 'You have already completed this task successfully and received your bits.');
        }
        
        // Check total submissions in the last 30 days (regardless of status)
        if ($task->max_submissions) {
            $userRecentSubmissions = BitSubmission::where('user_id', Auth::id())
                ->where('bit_task_id', $task->id)
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->count();
                
            if ($userRecentSubmissions >= $task->max_submissions) {
                return redirect()->back()->with('error', 'You have reached your maximum number of submissions for this task this month.');
            }
        }
        
        // Handle file uploads
        $proofPaths = [];
        if ($request->hasFile('proof')) {
            foreach($request->file('proof') as $file) {
                $proofPaths[] = Helpers::upload('bit-submissions/', config('fileformats.all'), $file);
            }
        }
        
        // Create submission
        BitSubmission::create([
            'user_id' => Auth::id(),
            'bit_task_id' => $task->id,
            'submission_content' => $request->submission_content,
            'proof' => !empty($proofPaths) ? json_encode($proofPaths) : null,
            'status' => 'pending'
        ]);
        
        // Increment total submissions count
        $task->increment('total_submissions');
        
        return redirect()->route('user.bit-tasks.show', $task->slug)
            ->with('success', 'Your submission has been received and is pending review.');
    }
    
    public function history()
    {
        $submissions = Auth::user()->bitSubmissions()
            ->with('task')
            ->latest()
            ->paginate(10);
            
        return view('user.bit-tasks.history', compact('submissions'));
    }
}
