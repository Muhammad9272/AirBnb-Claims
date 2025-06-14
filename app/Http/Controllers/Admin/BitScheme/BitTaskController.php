<?php

namespace App\Http\Controllers\Admin\BitScheme;

use App\Http\Controllers\Controller;
use App\Models\BitTask;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use DataTables;
use Validator;

class BitTaskController extends Controller
{
    protected function validateTask(Request $request, $id = null)
    {
        return Validator::make($request->all(), [
            'title' => [
                'required',
                'string',
                'max:255',
                $id ? 'unique:bit_tasks,title,' . $id : 'unique:bit_tasks,title'
            ],
            'description' => 'required|string',
            'bit_value' => 'required|integer|min:1',
            // 'status' => 'required|in:0,1',
            //'required_proof' => 'nullable|string|max:255',
            'max_submissions' => 'nullable|integer|min:1',
        ]);
    }

    public function index()
    {
        return view('admin.bits-scheme.tasks.index');
    }

    public function datatables()
    {
        $datas = BitTask::orderBy('id', 'desc')->get();

        return DataTables::of($datas)
            ->addIndexColumn()
            ->addColumn('status', function($row) {
                $class = $row->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $row->status == 1 ? 'selected' : '';
                $ns = $row->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks '.$class.'">
                    <option data-val="1" value="'.route('admin.bit-tasks.status', ['id' => $row->id, 'status' => 1]).'" '.$s.'>Activated</option>
                    <option data-val="0" value="'.route('admin.bit-tasks.status', ['id' => $row->id, 'status' => 0]).'" '.$ns.'>Deactivated</option>
                    </select></div>';
            })
            ->addColumn('submissions_count', function(BitTask $data) {
                $count = $data->submissions()->count();
                return $count > 0 ? 
                    '<p  class="badge bg-success">' . $count . '</p>' : 
                    '0';
            })
            ->addColumn('pending_count', function(BitTask $data) {
                $count = $data->submissions()->where('status', 'pending')->count();
                return $count > 0 ?
                    '<a href="' . route('admin.bit-submissions.pending') . '?task_id=' . $data->id . '" class="badge bg-warning">' . $count . ' pending</a>' :
                    '0';
            })
            ->addColumn('action', function(BitTask $data) {
                return '<div class="btn-group mb-1">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        Action
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="' . route('admin.bit-tasks.edit', $data->id) . '">
                            <i class="ri-pencil-fill me-2"></i> Edit
                        </a>
                        <a class="dropdown-item delete-item" href="javascript:;" data-bs-toggle="modal" data-bs-target="#deleteModal" data-href="' . route('admin.bit-tasks.delete', $data->id) . '">
                            <i class="ri-delete-bin-fill me-2"></i> Delete
                        </a>
                    </div>
                </div>';
            })
            ->rawColumns(['status', 'pending_count','submissions_count', 'action'])
            ->toJson();
    }

    public function create()
    {
        return view('admin.bits-scheme.tasks.create');
    }

    public function store(Request $request)
    {
        $validator = $this->validateTask($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        try {
            $data = $request->all();
            $data['slug'] = Helpers::slug($request->title);

            BitTask::create($data);

            return response()->json([
                'success' => true,
                'msg' => "Task has been created successfully!",
                'route' => route('admin.bit-tasks.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => "Error: " . $e->getMessage()
            ]);
        }
    }

    public function edit($id)
    {
        $data = BitTask::findOrFail($id);
        return view('admin.bits-scheme.tasks.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validateTask($request, $id);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        try {
            $task = BitTask::findOrFail($id);
            $data = $request->all();
            $data['slug'] = Helpers::slug($request->title);

            $task->update($data);

            return response()->json([
                'success' => true,
                'msg' => "Task has been updated successfully!",
                'route' => route('admin.bit-tasks.edit', $id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => "Error: " . $e->getMessage()
            ]);
        }
    }

    public function status($id, $status)
    {
        try {
            $task = BitTask::findOrFail($id);
            $task->status = $status; // 1 or 0
            $task->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $task = BitTask::findOrFail($id);

            if ($task->submissions()->where('status', 'approved')->exists()) {
                 return redirect()->route('admin.bit-tasks.index')
                ->with('error', 'Cannot delete task with approved submissions.');
            }

            $task->delete();

           return redirect()->route('admin.bit-tasks.index')
            ->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
             return redirect()->route('admin.bit-tasks.index')
                ->with('error', $e->getMessage());
        
        }
    }
}
