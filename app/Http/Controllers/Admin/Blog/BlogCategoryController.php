<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use DataTables;
use App\CentralLogics\Helpers;
use Validator;

class BlogCategoryController extends Controller
{
    protected function validateCategory(Request $request, $id = null)
    {
        return Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                $id ? 'unique:blog_categories,name,'.$id : 'unique:blog_categories,name'
            ],
            'description' => 'nullable|string',
            'photo' => 'nullable|image'
        ]);
    }

    public function index()
    {
        return view('admin.blog.category.index');
    }

    public function datatables()
    {
        $categories = BlogCategory::all();

        return DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('photo', function($row) {
                $photo = Helpers::image($row->photo, 'blog/category/');
                return '<img src="'.$photo.'" class="img-thumbnail" style="width:50px">';
            })
            ->addColumn('status', function($row) {
                $class = $row->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $row->status == 1 ? 'selected' : '';
                $ns = $row->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks '.$class.'">
                    <option data-val="1" value="'.route('admin.blog.category.status',['id1' => $row->id, 'id2' => 1]).'" '.$s.'>Activated</option>
                    <option data-val="0" value="'.route('admin.blog.category.status',['id1' => $row->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option>
                    </select></div>';
            })
            ->addColumn('action', function($row) {
                return '<div class="action-list">
                    <a href="'.route('admin.blog.category.edit', $row->id).'" class="btn btn-info btn-sm">
                        <i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit
                    </a>
                  
                </div>';
            })
            ->rawColumns(['photo', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.blog.category.create');
    }

    public function store(Request $request)
    {
        $validator = $this->validateCategory($request);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        try {
            $category = new BlogCategory();
            $input = $request->all();
            
            if ($request->hasFile('photo')) {
                $category->photo = Helpers::upload('blog/category/', config('fileformats.image'), $request->file('photo'));
            }
            
            $category->slug = Helpers::slug($request->name);
            $category->fill($input)->save();

            return response()->json([
                'success' => true,
                'msg' => "Category has been created successfully!",
                'route' => route('admin.blog.category.index')
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
        $category = BlogCategory::findOrFail($id);
        return view('admin.blog.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);
        $validator = $this->validateCategory($request, $id);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        try {
            $input = $request->all();

            if ($request->hasFile('photo')) {
                $category->photo = Helpers::update('blog/category/', $category->photo, 
                    config('fileformats.image'), $request->file('photo'));
            }

            $category->slug = Helpers::slug($request->name);
            $category->fill($input)->save();

            return response()->json([
                'success' => true,
                'msg' => "Category has been updated successfully!",
                'route' => route('admin.blog.category.edit', $id)
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
            $category = BlogCategory::findOrFail($id);
            
            if ($category->blogs()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with associated blogs.'
                ]);
            }
            
            if ($category->photo) {
                Helpers::unlink('blog/category/', $category->photo);
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
        $category = BlogCategory::findOrFail($id1);
        $category->status = $id2;
        $category->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
