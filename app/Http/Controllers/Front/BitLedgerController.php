<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\BitSubmission;
use App\Models\BitTask;
use Illuminate\Http\Request;

class BitLedgerController extends Controller
{
    /**
     * Display the public ledger of bit task submissions
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get all tasks for filtering
        $tasks = BitTask::where('status', 1)->get();
        
        // Base query for tasks
        $query = BitTask::where('status', 1)
            ->withCount(['submissions as total_submissions'])
            ->withCount(['submissions as approved_count' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->withCount(['submissions as processing_count' => function ($query) {
                $query->where('status', 'processing');
            }])
            ->withCount(['submissions as rejected_count' => function ($query) {
                $query->where('status', 'rejected');
            }])
            ->orderBy('created_at', 'desc');
        
        // Filter by status if requested
        if ($request->has('status') && $request->status) {
            $status = $request->status;
            $query->whereHas('submissions', function($q) use ($status) {
                $q->where('status', $status);
            });
        }
        
        // Paginate the results
        $bitTasks = $query->paginate(15);
        
        return view('front.bit-tasks.ledger', compact('bitTasks', 'tasks'));
    }
    
    /**
     * Display the details of a specific task with anonymized submissions
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $task = BitTask::where('status', 1)
            ->where('slug', $slug)
            ->withCount(['submissions as total_submissions'])
            ->withCount(['submissions as approved_count' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->withCount(['submissions as processing_count' => function ($query) {
                $query->where('status', 'processing');
            }])
            ->withCount(['submissions as rejected_count' => function ($query) {
                $query->where('status', 'rejected');
            }])
            ->firstOrFail();
        
        $submissions = BitSubmission::with(['user:id,name,email'])
            ->where('bit_task_id', $task->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('front.bit-tasks.task-details', compact('task', 'submissions'));
    }
}
