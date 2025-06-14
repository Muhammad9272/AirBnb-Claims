<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use DataTables;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;

use Validator;
class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display the DataTables data for categories/subcategories (server-side processing).
     *
     * @return \Illuminate\Http\Response
     */
    public function datatables(Request $request)
    {
        $categories = ProductCategory::with('subcategories')->get();

        return DataTables::of($categories)
             ->addIndexColumn()
            
            ->addColumn('status', function(ProductCategory $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.product-categories.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.product-categories.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
            })
             ->addColumn('parent_category', function(ProductCategory $data) {
                // Check if the category has a parent
                return $data->parentCategory ? $data->parentCategory->name : 'No Parent';
            })

            ->addColumn('action', function(ProductCategory $data) {

                 return '<div class="action-list">
                                
                                <a href="'.route('admin.product-categories.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> 


                                </div>';
            })
            ->rawColumns(['select', 'status', 'action'])
            ->make(true);
    }

    public function index()
    {
       
        return view('admin.products.category.index');
    }

    /**
     * Update the status of a category.
     *
     * @param  int  $id1
     * @param  int  $id2
     * @return \Illuminate\Http\Response
     */
    public function status($id1, $id2)
    {
        $category = ProductCategory::findOrFail($id1);
        $category->status = $id2;
        $category->save();

        // return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ProductCategory::all();
        return view('admin.products.category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules=[
            'name' => 'required|string|max:255|unique:product_categories,name',
            'parent_id' => 'nullable|exists:product_categories,id',
            'description' => 'nullable|string',
            'photo' => 'nullable|image',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = new ProductCategory();

        $input=$request->all();


        if ($request->hasFile('photo')) {
            $data->photo = Helpers::upload('category_photos/', config('fileformats.image'), $request->file('photo')); 
        }
        $data->slug=Helpers::slug($request->name);

        $data->fill($input)->save();

        // Session::flash('message', 'Data Added Successfully !');
        // Session::flash('alert-class', 'alert-success');
        return response()->json([
            'success' => true,
            'msg' => "Data has been saved successfully!",
            'route'=>route('admin.product-categories.index'),
        ]);


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = ProductCategory::findOrFail($id);
        $categories = ProductCategory::all();
        return view('admin.products.category.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rules=[
            'name' => 'required|string|max:255|unique:product_categories,name,'.$id,
            'parent_id' => 'nullable|exists:product_categories,id',
            'description' => 'nullable|string',
            'photo' => 'nullable|image',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = ProductCategory::findOrFail($id);
        $input=$request->all();

        if ($request->hasFile('photo')) {
            $data->photo = Helpers::update('category_photos/', $data->photo,config('fileformats.image'), $request->file('photo'));
        }
        $data->slug=Helpers::slug($request->name);


        $data->fill($input)->update();

        return response()->json([
            'success' => true,
            'msg' => "Data has been updated successfully",
            'route'=>route('admin.product-categories.edit',$data->id),
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        ProductCategory::destroy($id);
        return redirect()->route('admin.product-categories.index');
    }
}
