<?php

namespace App\Http\Controllers\Admin\Nostalgia;

use App\Http\Controllers\Controller;
use App\Models\NostalgiaCategory;
use Illuminate\Http\Request;
use DataTables;
use App\CentralLogics\Helpers;
use Validator;

class NostalgiaCategoryController extends Controller
{
    protected function validateCategory(Request $request, $id = null)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                $id ? 'unique:nostalgia_categories,name,'.$id : 'unique:nostalgia_categories,name'
            ],
            'description' => 'nullable|string',
            'photo' => 'nullable|image'
        ];

        // Only validate level and parent_id for new categories
        if (!$id) {
            $rules['level'] = 'required|in:1,2,3';
            $rules['parent_id'] = [
                'nullable',
                'exists:nostalgia_categories,id',
                function($attribute, $value, $fail) use ($request) {
                    // Skip validation if it's a main category
                    if ($request->level == 1) {
                        return;
                    }

                    // Parent is required for sub and child categories
                    if (empty($value)) {
                        $fail('Parent category is required.');
                        return;
                    }

                    // Validate parent exists
                    $parent = NostalgiaCategory::find($value);
                    if (!$parent) {
                        $fail('Selected parent category does not exist.');
                        return;
                    }

                    // Validate parent-child relationship
                    if ($request->level == 2 && $parent->level != 1) {
                        $fail('For sub-category, parent must be a main category.');
                        return;
                    }

                    if ($request->level == 3 && $parent->level != 2) {
                        $fail('For child category, parent must be a sub-category.');
                        return;
                    }
                }
            ];
        }

        return Validator::make($request->all(), $rules);
    }

    public function index()
    {
        return view('admin.nostalgia.category.index');
    }

    public function datatables()
    {
        $categories = NostalgiaCategory::with('parent')->get();

        return DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('level_name', function($row) {
                $badges = [
                    1 => '<span class="badge bg-primary">Main Category</span>',
                    2 => '<span class="badge bg-info">Sub Category</span>',
                    3 => '<span class="badge bg-secondary">Child Category</span>'
                ];
                return $badges[$row->level];
            })
            ->addColumn('parent_name', function($row) {
                return $row->parent ? $row->parent->name : 'N/A';
            })
            ->addColumn('status', function($row) {
                $class = $row->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $row->status == 1 ? 'selected' : '';
                $ns = $row->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks '.$class.'">
                    <option data-val="1" value="'. route('admin.nostalgia.category.status',['id1' => $row->id, 'id2' => 1]).'" '.$s.'>Activated</option>
                    <option data-val="0" value="'. route('admin.nostalgia.category.status',['id1' => $row->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option>
                    </select></div>';
            })
            ->addColumn('action', function($row) {
                return '<div class="action-list">
                    <a href="'.route('admin.nostalgia.category.edit', $row->id).'" class="btn btn-info btn-sm">
                        <i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit
                    </a>
                </div>';
            })
            ->rawColumns(['level_name', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $mainCategories = NostalgiaCategory::where('level', 1)->get();
        $subCategories = NostalgiaCategory::with('parent')->where('level', 2)->get();
        return view('admin.nostalgia.category.create', compact('mainCategories', 'subCategories'));
    }

    public function store(Request $request)
    {
        $validator = $this->validateCategory($request);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        try {
            $category = new NostalgiaCategory();
            $input = $request->all();
            
            if ($request->hasFile('photo')) {
                $category->photo = Helpers::upload('nostalgia/category_photos/', config('fileformats.image'), $request->file('photo'));
            }
            
            $category->slug = Helpers::slug($request->name);
            $category->fill($input)->save();

            return response()->json([
                'success' => true,
                'msg' => "Category has been created successfully!",
                'route' => route('admin.nostalgia.category.index')
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
        $category = NostalgiaCategory::findOrFail($id);
        $mainCategories = NostalgiaCategory::where('level', 1)->get();
        $subCategories = NostalgiaCategory::with('parent')->where('level', 2)->get();
        return view('admin.nostalgia.category.edit', compact('category', 'mainCategories', 'subCategories'));
    }

    public function update(Request $request, $id)
    {
        $category = NostalgiaCategory::findOrFail($id);
        
        // Merge existing level and parent_id to prevent changes
        $request->merge([
            'level' => $category->level,
            'parent_id' => $category->parent_id
        ]);
        
        $validator = $this->validateCategory($request, $id);
        
       if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        try {
            $input = $request->all();

            if ($request->hasFile('photo')) {
                $category->photo = Helpers::update('nostalgia/category_photos/', $category->photo, config('fileformats.image'), $request->file('photo'));
            }

            $category->slug = Helpers::slug($request->name);
            $category->fill($input)->save();

            return response()->json([
                'success' => true,
                'msg' => "Category has been updated successfully!",
                'route' => route('admin.nostalgia.category.edit', $id)
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
            $category = NostalgiaCategory::findOrFail($id);
            
            // Delete photo if exists
            if ($category->photo) {
                Helpers::unlink('nostalgia/category_photos/', $category->photo);
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
        $category = NostalgiaCategory::findOrFail($id1);
        $category->status = $id2;
        $category->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    public function getParentCategories(Request $request)
    {
        $level = $request->level;
        $currentId = $request->current_id ?? null; // For edit mode
        
        $query = NostalgiaCategory::where('status', 1)
            ->where('level', $level == 3 ? 2 : 1); // If level is 3, get level 2 parents, else get level 1
        
        if ($currentId) {
            $query->where('id', '!=', $currentId); // Exclude current category in edit mode
        }
        
        $categories = $query->get(['id', 'name']);
        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }
}
