<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use DataTables;
use Validator;
use Carbon\Carbon;
class ReviewController extends Controller
{
    public function index()
    {
        return view('admin.reviews.index');
    }
    
    public function datatables()
    {
        $reviews = ProductReview::with(['product', 'user'])->latest();

        return DataTables::of($reviews)
            ->addIndexColumn()
            ->addColumn('product', function($row) {
                return $row->product ? $row->product->name : 'N/A';
            })
            ->addColumn('customer', function($row) {
                return $row->user ? $row->user->name : 'N/A';
            })
            ->addColumn('rating', function($row) {
                return $row->getStarsHtml();
            })
            ->addColumn('status', function($row) {
                $class = $row->approved ? 'bg-success' : 'bg-warning';
                $status = $row->approved ? 'Approved' : 'Pending';
                return '<span class="badge ' . $class . '">' . $status . '</span>';
            })
            ->addColumn('verified', function($row) {
                return $row->verified_purchase ? '<span class="badge bg-info">Verified</span>' : '-';
            })
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d M, Y - h:i A');
            })
            ->addColumn('action', function($row) {
                return '<div class="btn-group mb-1">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        Action
                    </button>
                    <div class="dropdown-menu">
                        
                        <a class="dropdown-item" href="' . route('admin.reviews.edit', $row->id) . '">
                            <i class="ri-pencil-line me-2"></i> Edit
                        </a>
                        <a class="dropdown-item delete-item" href="javascript:;" data-bs-toggle="modal" data-bs-target="#deleteModal" data-href="' . route('admin.reviews.delete', $row->id) . '">
                            <i class="ri-delete-bin-line me-2"></i> Delete
                        </a>
                    </div>
                </div>';
            })
            ->rawColumns(['rating', 'status', 'verified', 'action'])
            ->make(true);
    }


   

    public function edit($id)
    {
        $review = ProductReview::with(['product', 'user'])->findOrFail($id);
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, $id)
    {



         $rules=[
            'approved' => 'nullable|boolean',
            'admin_reply' => 'nullable|string'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        
        $review = ProductReview::findOrFail($id);
        $review->approved = $request->approved;
        $review->admin_reply = $request->admin_reply;
        $review->save();
         return response()->json([
            'success' => true,
            'msg' => "Review updated successfully",
            'route'=>route('admin.reviews.index'),
        ]);
        
        
    }

    public function delete($id)
    {
        ProductReview::findOrFail($id)->delete();
        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully');
    }
    
    public function bulkApprove(Request $request)
    {
        $ids = $request->ids;
        ProductReview::whereIn('id', $ids)->update(['approved' => true]);
        
        return response()->json(['success' => true]);
    }
    
    public function bulkReject(Request $request)
    {
        $ids = $request->ids;
        ProductReview::whereIn('id', $ids)->update(['approved' => false]);
        // ProductReview::whereIn('id', $ids)->delete();
        
        return response()->json(['success' => true]);
    }
}
