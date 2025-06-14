<?php

namespace App\Http\Controllers\Admin\BitScheme;

use App\Http\Controllers\Controller;
use App\Models\BitSubmission;
use App\Models\BitTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Carbon\Carbon;

class BitSubmissionController extends Controller
{
    public function index()
    {
        return view('admin.bits-scheme.submissions.index');
    }
    
    public function datatables(Request $request)
    {
        $task_id = $request->task_id;
        $status = $request->status;
        
        $datas = BitSubmission::with(['user', 'task'])
                ->when($task_id, function($query) use ($task_id) {
                    return $query->where('bit_task_id', $task_id);
                })
                ->when($status, function($query) use ($status) {
                    return $query->where('status', $status);
                })
                ->orderBy('created_at', 'desc');
        
        return DataTables::of($datas)
            ->addIndexColumn()
            ->addColumn('user', function(BitSubmission $data) {
                return $data->user ? $data->user->name : 'N/A';
            })
            ->addColumn('task', function(BitSubmission $data) {
                return $data->task ? $data->task->title : 'N/A';
            })
            ->addColumn('value', function(BitSubmission $data) {
                return $data->task ? $data->task->bit_value . ' bits' : 'N/A';
            })
            ->addColumn('status', function(BitSubmission $data) {
                if ($data->status == 'approved') {
                    return '<span class="badge badge-soft-success">Approved</span>';
                } elseif ($data->status == 'rejected') {
                    return '<span class="badge badge-soft-danger">Rejected</span>';
                } else {
                    return '<span class="badge badge-soft-warning">Pending</span>';
                }
            })
            ->editColumn('created_at', fn($data) => $data->created_at->format('d M Y H:i a'))
            ->addColumn('action', function(BitSubmission $data) {
                $reviewBtn = '';
                if ($data->status == 'pending') {
                    $reviewBtn = '<a class="dropdown-item" href="' . route('admin.bit-submissions.show', $data->id) . '">
                        <i class="ri-eye-fill me-2"></i> Review
                    </a>';
                }
                
                return '<div class="btn-group mb-1">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" style="">
                        ' . $reviewBtn . '
                        <a class="dropdown-item" href="' . route('admin.bit-submissions.show', $data->id) . '">
                            <i class="ri-eye-fill me-2"></i> View Details
                        </a>
                    </div>
                </div>';
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }
    
    public function pending()
    {
        return view('admin.bits-scheme.submissions.pending');
    }
    
    public function show($id)
    {
        $data = BitSubmission::with(['user', 'task'])->findOrFail($id);
        
        // Count submissions from this user for this task in the last 30 days
        $userRecentSubmissionsCount = BitSubmission::where('user_id', $data->user_id)
            ->where('bit_task_id', $data->bit_task_id)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->count();
        
        // Count approved submissions from this user for this task
        $userApprovedCount = BitSubmission::where('user_id', $data->user_id)
            ->where('bit_task_id', $data->bit_task_id)
            ->where('status', 'approved')
            ->count();
        
        return view('admin.bits-scheme.submissions.show', compact('data', 'userRecentSubmissionsCount', 'userApprovedCount'));
    }
    
    public function review(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);
        
        $submission = BitSubmission::findOrFail($id);
        
        if ($submission->status !== 'pending') {
            return redirect()->back()->with('error', 'This submission has already been reviewed.');
        }
        
        $submission->status = $request->status;
        $submission->admin_notes = $request->admin_notes;
        
        if ($request->status === 'approved') {
            $submission->approved_at = now();
            $submission->approved_by = Auth::id();
            
            // Add bits to user's balance
            $user = User::find($submission->user_id);
            $task = BitTask::find($submission->bit_task_id);
            
            $user->addBits(
                $task->bit_value,
                'task',
                $task->id,
                "Earned from task: {$task->title}"
            );
        }
        
        $submission->save();
        
        return redirect()->route('admin.bit-submissions.index')
            ->with('success', "Submission {$request->status} successfully.");
    }
}
