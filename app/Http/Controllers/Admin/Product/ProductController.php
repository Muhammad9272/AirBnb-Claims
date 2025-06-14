<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\OptionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use DataTables;
use App\CentralLogics\Helpers;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    protected function validateProduct(Request $request, $isUpdate = false)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                $isUpdate ? 'unique:products,name,' . $request->segment(4) : 'unique:products,name'
            ],
            'sku' => 'nullable|string|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'subcategory_id' => function($attribute, $value, $fail) use ($request) {
                // If subcategories exist for selected category
                $hasSubcategories = ProductCategory::where('parent_id', $request->category_id)->exists();
                if ($hasSubcategories && empty($value)) {
                    $fail('The subcategory field is required when available.');
                }
                if (!empty($value)) {
                    if (!ProductCategory::where('id', $value)
                        ->where('parent_id', $request->category_id)
                        ->exists()) {
                        $fail('The selected subcategory is invalid.');
                    }
                }
            },
            'product_type' => 'required|in:console,accessory,repair_part',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
            'checks' => 'nullable|array',
            'checks.*' => 'in:featured,postage_eligible',
            'max_bits_allowed' => 'nullable|integer|min:0',
            
            // Option validations
            'option_types' => 'nullable|array',
            'option_types.*' => 'exists:option_types,id',
            'option_values' => 'nullable|array',
            'option_values.*.*' => 'required|string|max:255',
            'option_prices.*.*' => 'required|numeric|min:0'
        ];

        return Validator::make($request->all(), $rules);
    }

    public function datatables(Request $request)
    {
        $products = Product::with(['category', 'subcategory'])->get();

        return DataTables::of($products)
            ->addIndexColumn()
            ->addColumn('photo', function(Product $product) {

                $photo =Helpers::image($product->main_image, 'products/');
               
                return '<img src="' . $photo . '" class="img-thumbnail object-cover" style="width:80px;height:80px">';
            })
            ->addColumn('category', function(Product $product) {
                return $product->category ? $product->category->name : 'No Category';
            })
            ->addColumn('price', function(Product $product) {
                return Helpers::setCurrency($product->price);
            })
            ->addColumn('quantity', function(Product $product) {
                $class = $product->quantity > 0 ? 'text-success' : 'text-danger';
                return '<span class="' . $class . '">' . $product->quantity . '</span>';
            })
            ->addColumn('status', function(Product $product) {
                $class = $product->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $product->status == 1 ? 'selected' : '';
                $ns = $product->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks '.$class.'">
                    <option data-val="1" value="'. route('admin.product.status',['id1' => $product->id, 'id2' => 1]).'" '.$s.'>Activated</option>
                    <option data-val="0" value="'. route('admin.product.status',['id1' => $product->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option>
                    </select></div>';
            })
            ->addColumn('action', function(Product $product) {
                return '<div class="action-list">
                    <a href="'.route('admin.product.edit', $product->id).'" class="btn btn-info btn-sm">
                        <i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit
                    </a>
                    
                </div>';
                // <button class="btn btn-danger btn-sm delete-product" data-id="'.$product->id.'">
                //         <i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete
                //     </button>
            })
            ->rawColumns(['photo', 'quantity', 'status', 'action'])
            ->make(true);
    }

    public function index()
    {
        return view('admin.products.listing.index');
    }

    public function create()
    {
        $categories = ProductCategory::whereNull('parent_id')->get();
        $subcategories = ProductCategory::whereNotNull('parent_id')->get();
        $optionTypes = OptionType::all();
                
        return view('admin.products.listing.create', compact('categories', 'subcategories', 'optionTypes'));
        }

    protected function handleGalleryUpload($request, $product = null)
    {
        $gallery = [];
        $mainImageOriginal = $request->main_image ?? null;
        $mainImageNew = null;
        $fileNameMap = [];

        // Handle existing gallery images for updates
        if ($product && !empty($product->gallery) && !is_null($product->gallery)) {
            foreach (json_decode($product->gallery) as $file) {
                if (!is_null($request->old) && in_array($file, $request->old)) {
                    $gallery[] = $file;
                    
                    if ($mainImageOriginal === $file) {
                        $mainImageNew = $file;
                    }
                } else {
                    Helpers::unlink('products/', $file);
                }
            }
        }

        // Process newly uploaded files
        if ($request->gallery) {
            foreach ($request->gallery as $photo) {
                // Create a file object from temporary storage
                $file = new \Illuminate\Http\UploadedFile(
                    storage_path('tmp/uploads/' . $photo), 
                    $photo, 
                    null, 
                    null, 
                    true
                );
                
                $newFileName = Helpers::upload('products/', config('fileformats.image'), $file);
                
                $gallery[] = $newFileName;
                $fileNameMap[$photo] = $newFileName;
                
                if ($mainImageOriginal === $photo) {
                    $mainImageNew = $newFileName;
                }
            }
        }

        // Set first image as main if none selected
        if (empty($mainImageNew) && !empty($gallery)) {
            $mainImageNew = $gallery[0];
        }

        return [
            'gallery' => empty($gallery) ? null : json_encode($gallery),
            'main_image' => $mainImageNew
        ];
    }

    protected function handleRequest($request, $product = null)
    {
        try {
            DB::beginTransaction();

            $isUpdate = !is_null($product);
            
            if (!$isUpdate) {
                $product = new Product();
            }

            // Basic product info
            $product->name = $request->name;
            $product->slug = Helpers::slug($request->name);
            $product->sku = $request->sku;
            $product->category_id = $request->category_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->product_type = $request->product_type;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->max_bits_allowed = $request->input('max_bits_allowed', 10);
            $product->paypostage_price = null;
            $product->paypostage_stock = null;
            if($request->checks && in_array('postage_eligible', $request->checks)) {
                $product->paypostage_price = $request->price;
                $product->paypostage_stock = $request->quantity;
            }
            // Handle gallery and main image
            $galleryData = $this->handleGalleryUpload($request, $isUpdate ? $product : null);
            $product->gallery = $galleryData['gallery'];
            $product->main_image = $galleryData['main_image']; // Use main_image as photo

            // Handle other JSON fields
            if ($request->has('tags')) {
                $tags = array_filter(array_map('trim', explode(',', $request->tags)));
                $product->tags = !empty($tags) ? json_encode($tags) : null;
            }

            if ($request->has('checks')) {
                $product->checks = !empty($request->checks) ? json_encode($request->checks) : null;
            }

            // Handle variations as JSON - Modified to prevent duplicates
            if ($request->has('option_types')) {
                $variations = [];
                $processedTypes = []; // Track processed option types
                
                foreach ($request->option_types as $index => $typeId) {
                    // Skip if we've already processed this option type
                    if (in_array($typeId, $processedTypes)) {
                        continue;
                    }
                    
                    $processedTypes[] = $typeId;

                    if (isset($request->option_values[$typeId])) {
                        $values = $request->option_values[$typeId];
                        $prices = $request->option_prices[$typeId];
                        $optionValues = [];

                        foreach ($values as $key => $value) {
                            if (!empty($value)) { // Only add if value is not empty
                                $optionValues[] = [
                                    'value' => $value,
                                    'additional_price' => $prices[$key] ?? 0,
                                    'status' => 1
                                ];
                            }
                        }

                        // Only add if there are values
                        if (!empty($optionValues)) {
                            $variations[] = [
                                'option_type_id' => $typeId,
                                'option_type_name' => OptionType::find($typeId)->name,
                                'values' => $optionValues
                            ];
                        }
                    }
                }

                $product->variations = !empty($variations) ? json_encode($variations) : null;
            }

            $product->save();
     
            DB::commit();
            
            return [
                'success' => true,
                'message' => $isUpdate ? 'Product updated successfully' : 'Product created successfully',
                'redirect' => route('admin.product.index')
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Error ' . ($isUpdate ? 'updating' : 'creating') . ' product: ' . $e->getMessage()
            ];
        }
    }

    public function store(Request $request)
    {
        $validator = $this->validateProduct($request);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->handleRequest($request);
        return response()->json([
            'success' => true,
            'msg' => "Data has been saved successfully!",
            'route'=>route('admin.product.index'),
        ]);
        
    }

    public function edit($id)
    {
        $data = Product::with(['category', 'subcategory'])->findOrFail($id);
        $categories = ProductCategory::whereNull('parent_id')->get();
        $subcategories = ProductCategory::whereNotNull('parent_id')->get();
        $optionTypes = OptionType::all();


        return view('admin.products.listing.edit', compact('data', 'categories', 'subcategories', 'optionTypes'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validator = $this->validateProduct($request, true);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->handleRequest($request, $product);
        return response()->json([
            'success' => true,
            'msg' => "Data has been updated successfully",
            'route'=>route('admin.product.edit',$product->id),
        ]);
    }

    public function destroy($id)
    {
        Product::destroy($id);
        return redirect()->route('admin.product.index');
    }

    public function status($id1, $id2)
    {
        $product = Product::findOrFail($id1);
        $product->status = $id2;
        $product->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    public function getSubcategories($categoryId)
    {
        $subcategories = ProductCategory::where('parent_id', $categoryId)->get();
        return response()->json([
            'success' => true,
            'subcategories' => $subcategories
        ]);
    }
}
