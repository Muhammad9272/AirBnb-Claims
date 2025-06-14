<?php

namespace App\Http\Controllers\Admin\Nostalgia;

use App\Http\Controllers\Controller;
use App\Models\NostalgiaItem;
use App\Models\NostalgiaCategory;
use Illuminate\Http\Request;
use DataTables;
use App\CentralLogics\Helpers;
use Validator;

class NostalgiaItemController extends Controller
{
    protected function validateItem(Request $request, $id = null)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                $id ? 'unique:nostalgia_items,name,'.$id : 'unique:nostalgia_items,name'
            ],
            'category_id' => 'required|exists:nostalgia_categories,id',
            'subcategory_id' => [
                'nullable',
                'exists:nostalgia_categories,id',
                function($attribute, $value, $fail) use ($request) {
                   
                    // Check if subcategories exist for selected category
                    $hasSubcategories = NostalgiaCategory::where('parent_id', $request->category_id)
                        ->where('level', 2)
                        ->exists();


                    if ($hasSubcategories && empty($value)) {
                        $fail('Subcategory is required for this main category.');
                        return;
                    }

                    if (!empty($value)) {
                        $category = NostalgiaCategory::find($value);
                        if (!$category || $category->level != 2 || $category->parent_id != $request->category_id) {
                            $fail('Invalid subcategory selected.');
                        }
                    }
                }
            ],
            'childcategory_id' => [
                'nullable',
                'exists:nostalgia_categories,id',
                function($attribute, $value, $fail) use ($request) {
                    if (!empty($request->subcategory_id)) {
                        // Check if child categories exist for selected subcategory
                        $hasChildCategories = NostalgiaCategory::where('parent_id', $request->subcategory_id)
                            ->where('level', 3)
                            ->exists();

                        if ($hasChildCategories && empty($value)) {
                            $fail('Child category is required for this subcategory.');
                            return;
                        }
                    }

                    if (!empty($value)) {
                        $category = NostalgiaCategory::find($value);
                        if (!$category || $category->level != 3 || !$request->subcategory_id || $category->parent_id != $request->subcategory_id) {
                            $fail('Invalid child category selected.');
                        }
                    }
                }
            ],
            'description' => 'nullable|string',
            'release_year' => 'nullable|integer|min:1900|max:'.(date('Y')),
            'tags' => 'nullable|string',
            'specifications' => 'nullable|array',
            'specifications.release_type' => 'nullable|string',
            'specifications.prototype' => 'nullable|string',
            'specifications.regional_code' => 'nullable|string',
            'specifications.color' => 'nullable|string',
            'specifications.desire' => 'nullable|integer|min:0|max:10',
            'specifications.country' => 'nullable|string',
            'specifications.release_date' => 'nullable|date',
            'external_resources' => 'nullable|array',
            'external_resources.external_links' => 'nullable|string',
            'external_resources.common_faults' => 'nullable|string',
            'external_resources.guides' => 'nullable|string',
            'external_resources.buy_links' => 'nullable|string'
        ];

        return Validator::make($request->all(), $rules);
    }

    protected function processJsonFields($request)
    {
        $fields = [];

        // Process tags - ensure proper JSON array storage
        if ($request->has('tags')) {
            $tags = $request->tags ? array_filter(array_map('trim', explode(',', $request->tags))) : [];
            $fields['tags'] = !empty($tags) ? json_encode($tags) : null;
        }

        // Process external resources
        if ($request->has('external_resources')) {
            $resources = $request->external_resources;
            $processedResources = [];
            
            foreach ($resources as $key => $value) {
                if (!empty($value)) {
                    $processedResources[$key] = array_filter(array_map('trim', explode(',', $value)));
                }
            }
            
            $fields['external_resources'] = !empty($processedResources) ? 
                json_encode($processedResources) : null;
        }

        return $fields;
    }

    public function index()
    {
        return view('admin.nostalgia.items.index');
    }

    public function datatables()
    {
        $items = NostalgiaItem::with(['category', 'subcategory', 'childcategory'])->get();

        return DataTables::of($items)
            ->addIndexColumn()
            ->addColumn('photo', function($item) {
                $photo = Helpers::image($item->main_image, 'nostalgia/items/');
                return '<img src="'.$photo.'" class="img-thumbnail" style="width:50px">';
            })
            ->addColumn('category_info', function($item) {
                $info = $item->category->name;
                if ($item->subcategory) {
                    $info .= ' > ' . $item->subcategory->name;
                }
                if ($item->childcategory) {
                    $info .= ' > ' . $item->childcategory->name;
                }
                return $info;
            })
            ->addColumn('status', function($item) {
                $class = $item->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $item->status == 1 ? 'selected' : '';
                $ns = $item->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks '.$class.'">
                    <option data-val="1" value="'.route('admin.nostalgia.item.status',['id1' => $item->id, 'id2' => 1]).'" '.$s.'>Activated</option>
                    <option data-val="0" value="'.route('admin.nostalgia.item.status',['id1' => $item->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option>
                    </select></div>';
            })
            ->addColumn('action', function($item) {
                return '<div class="action-list">
                    <a href="'.route('admin.nostalgia.item.edit', $item->id).'" class="btn btn-info btn-sm">
                        <i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit
                    </a>
                    <button class="btn btn-danger btn-sm delete-item" data-id="'.$item->id.'">
                        <i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete
                    </button>
                </div>';
            })
            ->rawColumns(['photo', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $categories = NostalgiaCategory::where('level', 1)->where('status', 1)->get();
        return view('admin.nostalgia.items.create', compact('categories'));
    }

    protected function handleGalleryUpload($request, $item = null)
    {
        $gallery = [];
        $mainPhoto = null;

        // Handle existing gallery images for updates
        if ($item && !empty($item->gallery)) {
            foreach (json_decode($item->gallery) as $file) {
                if (!is_null($request->old) && in_array($file, $request->old)) {
                    $gallery[] = $file;
                } else {
                    Helpers::unlink('nostalgia/items/', $file);
                }
            }
        }

        // Process new uploads
        if ($request->gallery) {
            foreach ($request->gallery as $photo) {
                $file = new \Illuminate\Http\UploadedFile(
                    storage_path('tmp/uploads/' . $photo),
                    $photo,
                    null,
                    null,
                    true
                );
                
                $fileName = Helpers::upload('nostalgia/items/', config('fileformats.image'), $file);
                $gallery[] = $fileName;

                // Set first image as main photo if none selected
                if (empty($mainPhoto)) {
                    $mainPhoto = $fileName;
                }
            }
        }

        return [
            'gallery' => empty($gallery) ? null : json_encode($gallery),
            'main_image' => $mainPhoto
        ];
    }

    public function store(Request $request)
    {
        $validator = $this->validateItem($request);
        
       if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        try {
            $item = new NostalgiaItem();
            $input = $request->except(['tags', 'external_resources']);

            // Handle gallery upload
            $galleryData = $this->handleGalleryUpload($request);
            if (!empty($galleryData['gallery'])) {
                $item->gallery = $galleryData['gallery'];
                $item->main_image = $galleryData['main_image'];
            }

            // Process JSON fields
            $jsonFields = $this->processJsonFields($request);
            $item->fill($input);
            $item->fill($jsonFields);
            
            $item->slug = Helpers::slug($request->name);
            $item->save();

            return response()->json([
                'success' => true,
                'msg' => "Item has been created successfully!",
                'route' => route('admin.nostalgia.item.index')
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
        $data = NostalgiaItem::with(['category', 'subcategory', 'childcategory'])->findOrFail($id);
        $categories = NostalgiaCategory::where('level', 1)->where('status', 1)->get();
        $subcategories = $data->category ? NostalgiaCategory::where('parent_id', $data->category_id)->get() : collect();
        $childcategories = $data->subcategory ? NostalgiaCategory::where('parent_id', $data->subcategory_id)->get() : collect();
        
       
        return view('admin.nostalgia.items.edit', compact('data', 'categories', 'subcategories', 'childcategories'));
    }

    public function update(Request $request, $id)
    {
        $item = NostalgiaItem::findOrFail($id);
        $validator = $this->validateItem($request, $id);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        try {
            $input = $request->except(['tags', 'external_resources']);

            // Handle gallery upload
            $galleryData = $this->handleGalleryUpload($request, $item);
            if (!empty($galleryData['gallery'])) {
                $item->gallery = $galleryData['gallery'];
                $item->main_image = $galleryData['main_image'] ?: $item->main_image;
            }

            // Process JSON fields
            $jsonFields = $this->processJsonFields($request);
            $item->fill($input);
            $item->fill($jsonFields);
            
            $item->slug = Helpers::slug($request->name);
            $item->save();

            return response()->json([
                'success' => true,
                'msg' => "Item has been updated successfully!",
                'route' => route('admin.nostalgia.item.edit', $id)
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
            $item = NostalgiaItem::findOrFail($id);
            
            // Delete photos
            if ($item->main_image) {
                Helpers::unlink('nostalgia/items/', $item->main_image);
            }
            
            if ($item->gallery) {
                foreach (json_decode($item->gallery) as $photo) {
                    Helpers::unlink('nostalgia/items/', $photo);
                }
            }
            
            $item->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully.'
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
        $item = NostalgiaItem::findOrFail($id1);
        $item->status = $id2;
        $item->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    public function getSubcategories($category_id)
    {
        $subcategories = NostalgiaCategory::where('parent_id', $category_id)
            ->where('level', 2)
            ->where('status', 1)
            ->get(['id', 'name']);
            
        return response()->json([
            'success' => true,
            'subcategories' => $subcategories
        ]);
    }

    public function getChildcategories($subcategory_id)
    {
        $childcategories = NostalgiaCategory::where('parent_id', $subcategory_id)
            ->where('level', 3)
            ->where('status', 1)
            ->get(['id', 'name']);
            
        return response()->json([
            'success' => true,
            'childcategories' => $childcategories
        ]);
    }
}
