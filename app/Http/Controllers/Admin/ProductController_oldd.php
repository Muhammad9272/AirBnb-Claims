<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubPlan;
use DataTables;
use Illuminate\Http\Request;
use Validator;
use App\CentralLogics\Helpers;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }


    public function datatables()
    {   
        $datas = Product::orderBy('id', 'desc')->get();  
        return DataTables::of($datas)
            ->addIndexColumn()
            ->addColumn('name', function(Product $data) {
                return '<div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm bg-light rounded p-1">
                                    <img src="'.Helpers::image($data->image, 'product/').'" alt="" class="img-fluid d-block" style="max-width: 40px; max-height: 40px;">
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-14 mb-1">'.$data->name.'</h5>
                            </div>
                        </div>';
            })
            // ->addColumn('requirements', function(Product $data) {
            //     return '<div class="text-muted">'.Str::limit($data->requirements, 30).'</div>';
            // })
            ->addColumn('supported_os', function(Product $data) {
                return '<div class="text-muted">'.Str::limit($data->supported_os, 30).'</div>';
            })
            ->addColumn('supported_platforms', function(Product $data) {
                return '<div class="text-muted">'.Str::limit($data->supported_platforms, 30).'</div>';
            })
            ->addColumn('status', function(Product $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';
                return '<div class="action-list">
                            <select class="process select droplinks '.$class.'">
                                <option data-val="1" value="'. route('admin.product.status', ['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option>
                                <option data-val="0" value="'. route('admin.product.status', ['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option>
                            </select>
                        </div>';
            })
            ->addColumn('action', function(Product $data) {
                return '<div class="action-list">
                            <a href="'.route('admin.product.edit', $data->id).'" class="btn btn-info btn-sm fs-13">
                                <i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit
                            </a>
                        </div>';
            })
            ->rawColumns(['name', 'supported_os', 'supported_platforms', 'status', 'action'])
            ->toJson();
    }


    public function index()
    {
        return view('admin.products.listings.index');
    }

    public function create()
    {
        $categories = Category::active()->get();
        $subscriptionPlans = SubPlan::active()->get();
        return view('admin.products.listings.create', compact('categories', 'subscriptionPlans'));
    }

    public function store(Request $request)
    {    
        // Validation rules
        $rules = [
            'name'                => 'required|string|max:255|unique:products,name',  // Ensure the product name is unique
            'description'         => 'required|string',
            'requirements'        => 'nullable|string',
            'supported_os'        => 'required|string',
            'supported_platforms' => 'required|string',
            // 'subplan_id'          => 'required|exists:sub_plans,id',
            'image'               => 'required|image',
            'video'               => 'nullable|mimes:mp4,mov,avi',
            'pricing_type'        => 'required|in:subscription,single',
            'single_price'        => 'required_if:pricing_type,single|nullable|numeric|min:0',
            'access_duration'     => 'nullable|integer|min:1',
            'subscription_plans'  => 'required_if:pricing_type,subscription|array',
            'subscription_plans.*'=> 'exists:sub_plans,id',
            'protected_content'   => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }

        $data = new Product();
        // Assign the basic fields
        $data->name                = $request->name;
        $data->description         = $request->description;
        $data->requirements        = $request->requirements;
        $data->supported_os        = $request->supported_os;
        $data->supported_platforms = $request->supported_platforms;
        $data->detection_status = $request->detection_status;
        $data->pricing_type = $request->pricing_type;
        if ($request->pricing_type === 'single') {
            $data->single_price = $request->single_price;
            $data->access_duration = $request->access_duration;
        } else {
            $data->subscription_plans = json_encode($request->subscription_plans);
        }
        $data->protected_content = $request->protected_content;
        // $data->subplan_id          = $request->subplan_id;

        // Generate the slug based on the product name
        $data->slug                = Helpers::slug($request->name);

        // Upload image
        $data->image = Helpers::upload('product/', config('fileformats.image'), $request->file('image'));

        // Upload video if provided
        if ($request->hasFile('video')) {
            $data->video = Helpers::upload('product/video/', config('fileformats.video'), $request->file('video'));
        }

        // Save the product
        $data->save();

        return response()->json([
            'success' => true,
            'msg'     => "Product has been saved successfully!",
            'route'   => route('admin.product.edit', $data->id),
        ]);
    }

    public function edit($id)
    { 
        $data = Product::findOrFail($id);
        $subscriptionPlans = SubPlan::active()->get();
        $categories = Category::active()->get();
        return view('admin.products.listings.edit', compact('data', 'categories', 'subscriptionPlans'));
    }

    public function update(Request $request, $id)
    {
        // Validation rules
        $rules = [
            'name'                => 'required|string|max:255|unique:products,name,' . $id,  // Ensure the product name is unique, except the current product
            'description'         => 'required|string',
            'requirements'        => 'nullable|string',
            'supported_os'        => 'required|string',
            'supported_platforms' => 'required|string',
            // 'subplan_id'          => 'required|exists:sub_plans,id',
            'image'               => 'nullable|image',
            'video'               => 'nullable|mimes:mp4,mov,avi',
            'pricing_type'        => 'required|in:subscription,single',
            'single_price'        => 'required_if:pricing_type,single|nullable|numeric|min:0',
            'access_duration'     => 'nullable|integer|min:1',
            'subscription_plans'  => 'required_if:pricing_type,subscription|array',
            'subscription_plans.*'=> 'exists:sub_plans,id',
            'protected_content'   => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }

        $data = Product::findOrFail($id);
        $data->name                = $request->name;
        $data->description         = $request->description;
        $data->requirements        = $request->requirements;
        $data->supported_os        = $request->supported_os;
        $data->supported_platforms = $request->supported_platforms;
        $data->detection_status = $request->detection_status;
        $data->pricing_type = $request->pricing_type;
        if ($request->pricing_type === 'single') {
            $data->single_price = $request->single_price;
            $data->access_duration = $request->access_duration;
        } else {
            $data->subscription_plans = json_encode($request->subscription_plans);
        }
        $data->protected_content = $request->protected_content;
        // $data->subplan_id          = $request->subplan_id;

        // Generate the slug based on the product name
        $data->slug                = Helpers::slug($request->name);

        // Update image if a new file is provided
        if ($request->hasFile('image')) {
            $data->image = Helpers::update('product/', $data->image, config('fileformats.image'), $request->file('image'));
        }

        // Update video if a new file is provided
        if ($request->hasFile('video')) {
            $data->video = Helpers::update('product/video/', $data->video, config('fileformats.video'), $request->file('video'));
        }

        $data->update();

        return response()->json([
            'success' => true,
            'msg'     => "Product has been updated successfully!",
            'route'   => route('admin.product.edit', $data->id),
        ]);
    }

    public function status($id1, $id2)
    {
        $data = Product::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    public function destroy($id)
    {
        $data = Product::findOrFail($id);
        // Optionally, add code to delete associated files
        // $data->delete();
    }
}
