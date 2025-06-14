<?php

namespace App\Http\Controllers\Admin\Services;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use DataTables;
use App\CentralLogics\Helpers;
use Validator;

class ServiceCategoryController extends Controller
{
    protected function validateCategory(Request $request, $id = null)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                $id ? 'unique:service_categories,name,'.$id : 'unique:service_categories,name'
            ],
            'description' => 'nullable|string',
            'photo' => 'nullable|image'
        ];

        return Validator::make($request->all(), $rules);
    }

    public function index()
    {
        return view('admin.services.category.index');
    }

    public function datatables()
    {
        $categories = ServiceCategory::all();

        return DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('photo', function($row) {
                $photo = Helpers::image($row->photo, 'services/category/');
                return '<img src="'.$photo.'" class="img-thumbnail" style="width:50px">';
            })
            ->addColumn('status', function($row) {
                $class = $row->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $row->status == 1 ? 'selected' : '';
                $ns = $row->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks '.$class.'">
                    <option data-val="1" value="'. route('admin.service.category.status',['id1' => $row->id, 'id2' => 1]).'" '.$s.'>Active</option>
                    <option data-val="0" value="'. route('admin.service.category.status',['id1' => $row->id, 'id2' => 0]).'" '.$ns.'>Inactive</option>
                    </select></div>';
            })
            ->addColumn('action', function($row) {
                return '<div class="action-list">
                    <a href="'.route('admin.service.category.edit', $row->id).'" class="btn btn-info btn-sm">
                        <i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit
                    </a>
                    <button class="btn btn-danger btn-sm delete-item" data-id="'.$row->id.'">
                        <i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete
                    </button>
                </div>';
            })
            ->rawColumns(['photo', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.services.category.create');
    }

    public function store(Request $request)
    {
        $validator = $this->validateCategory($request);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        try {
            $category = new ServiceCategory();
            $input = $request->all();
            
            if ($request->hasFile('photo')) {
                $category->photo = Helpers::upload('services/category/', config('fileformats.image'), $request->file('photo'));
            }
            
            $category->slug = Helpers::slug($request->name);
            $category->fill($input)->save();

            return response()->json([
                'success' => true,
                'msg' => "Category has been created successfully!",
                'route' => route('admin.service.category.index')
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
        $category = ServiceCategory::findOrFail($id);
        return view('admin.services.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = ServiceCategory::findOrFail($id);
        $validator = $this->validateCategory($request, $id);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        try {
            $input = $request->all();

            if ($request->hasFile('photo')) {
                $category->photo = Helpers::update('services/category/', $category->photo, 
                    config('fileformats.image'), $request->file('photo'));
            }

            $category->slug = Helpers::slug($request->name);
            $category->fill($input)->save();

            return response()->json([
                'success' => true,
                'msg' => "Category has been updated successfully!",
                'route' => route('admin.service.category.edit', $id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => "Error: " . $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $category = ServiceCategory::findOrFail($id);
            
            // Delete photo if exists
            if ($category->photo) {
                Helpers::unlink('services/category/', $category->photo);
            }
            
            // Check if category has associated services
            if ($category->services()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with associated services.'
                ]);
            }
            
            $category->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function status($id1, $id2)
    {
        try {
            $category = ServiceCategory::findOrFail($id1);
            $category->status = $id2;
            $category->save();

            return response()->json([
                'success' => true, 
                'message' => 'Status updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
