<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use DataTables;
use Illuminate\Http\Request;
use Validator;
use DB;
class CouponController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables(Request $request)
    {   
        if($request->coupon_type=='auto'){

            $datas = User::select('users.*', DB::raw('(SELECT COUNT(*) FROM orders WHERE orders.coupon_code = users.affiliate_code) as order_count'), DB::raw('(SELECT SUM(pay_amount) FROM orders WHERE orders.coupon_code = users.affiliate_code) as total_sales'))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('orders')
                      ->whereColumn('orders.coupon_code', 'users.affiliate_code');
            })
            ->orderBy('order_count', 'desc')
            ->get();
            
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('coupon_name', function(User $data) {
                    return ucfirst($data->name);
                })  
                ->addColumn('coupon_code', function(User $data) {
                    return $data->affiliate_code;
                })
                ->addColumn('uses', function(User $data) {
                    return $data->order_count;
                    // $max_usage_count=$data->max_usage_count>0?$data->max_usage_count:'Unlimited';
                    // return '<p>'.$data->usage_count.'/'.$max_usage_count.'</p>';
                })   
                ->addColumn('sales', function(User $data) {
                      
                      return Helpers::setCurrency($data->total_sales);
                })                    
                ->addColumn('status', function(User $data) {
                    return '<span class="badge badge-soft-success text-uppercase">Active</span>';
                    
                }) 
                ->addColumn('action', function(User $data) {
                   return '<div class="action-list">
                    <a href="'.route('admin.users.show',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> 
                    </div>';
                }) 
                ->rawColumns(['uses','status','action'])
                ->toJson(); //--- Returning Json Data To Client Side

        }else{
            $datas=Coupon::orderBy('id','desc')->get();  
                    return DataTables::of($datas)
                            ->addIndexColumn()
                            ->addColumn('coupon_name', function(Coupon $data) {
                                return $data->coupon_name;
                            })  
                            ->addColumn('coupon_code', function(Coupon $data) {
                                return $data->coupon_code;
                            })
                            ->addColumn('uses', function(Coupon $data) {
                                $max_usage_count=$data->max_usage_count>0?$data->max_usage_count:'Unlimited';
                                return '<p>'.$data->usage_count.'/'.$max_usage_count.'</p>';
                            })   
                            ->addColumn('sales', function(Coupon $data) {
                                $sales=Order::where('coupon_code',$data->coupon_code)->sum('pay_amount');
                                  return Helpers::setCurrency($sales);
                            })                    
                            ->addColumn('status', function(Coupon $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.coupon.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.coupon.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            }) 
                            ->addColumn('action', function(Coupon $data) {
                               return '<div class="action-list">
                                <a href="'.route('admin.coupon.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> 
                                </div>';
                            }) 
                            ->rawColumns(['uses','status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side

        }

       
    }

    //*** GET Request
    public function index(Request $request)
    {
        // $datas = User::select('users.*', DB::raw('(SELECT COUNT(*) FROM orders WHERE orders.coupon_code = users.affiliate_code) as order_count'))
        //             ->orderBy('order_count', 'desc')
        //             ->get();


        // $datas = User::select('users.*', DB::raw('(SELECT COUNT(*) FROM orders WHERE orders.coupon_code = users.affiliate_code) as order_count'), DB::raw('(SELECT SUM(pay_amount) FROM orders WHERE orders.coupon_code = users.affiliate_code) as total_sales'))
        //     ->whereExists(function ($query) {
        //         $query->select(DB::raw(1))
        //               ->from('orders')
        //               ->whereColumn('orders.coupon_code', 'users.affiliate_code');
        //     })
        //     ->orderBy('order_count', 'desc')
        //     ->get();
        // dd($datas);
        if($request->coupon_type){
           $coupon_type = $request->coupon_type;
        }else{
            $coupon_type="manual";
        }
        return view('admin.coupon.index',compact('coupon_type'));
    }

    //*** GET Request
    public function create()
    {
        return view('admin.coupon.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section

        $rules = [
            'coupon_code' => 'required|unique:coupons,coupon_code',
            'coupon_name' => 'required',
            'discount' => 'nullable|numeric|between:0,99.99',
            'earnings' => 'nullable|numeric|between:0,99.99',
            // 'discount' => 'required|numeric',
            // 'earnings' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'max_usage_count' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        if(User::where('affiliate_code', '=', $request->coupon_code)->exists()){
           return response()->json(['errors' => ['coupon_code' => ['Coupon code cannot be used as it conflicts with an existing user affiliate code']]]);
        }   
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Coupon();
        $input = $request->all();
        // $input['start_date'] = Carbon::parse($input['start_date'])->format('Y-m-d H:i:s');
        // $input['end_date'] = Carbon::parse($input['end_date'])->format('Y-m-d H:i:s');
        
        
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section        
        return response()->json([
            'success' => true,
            'msg' => "Data has been saved successfully!",
            'route'=>route('admin.coupon.edit',$data->id),
        ]);    
        //--- Redirect Section Ends   
    }

    //*** GET Request
    public function edit($id)
    {
        $data = Coupon::findOrFail($id);
        return view('admin.coupon.edit',compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            'coupon_code' => 'required|unique:coupons,coupon_code,'.$id,
            'coupon_name' => 'required',
            'discount' => 'nullable|numeric|between:0,99.99',
            'earnings' => 'nullable|numeric|between:0,99.99',
            // 'discount' => 'required|numeric',
            // 'earnings' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'max_usage_count' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }        
        if(User::where('affiliate_code', '=', $request->coupon_code)->exists()){
           return response()->json(['errors' => ['coupon_code' => ['Coupon code cannot be used as it conflicts with an existing user affiliate code']]]);
        }   
        //--- Validation Section Ends

        //--- Logic Section
        $data = Coupon::findOrFail($id);
        $input = $request->all();
        
        $data->update($input);
        //--- Logic Section Ends
        
        //--- Redirect Section   
        return response()->json([
            'success' => true,
            'msg' => "Data has been updated successfully",
            'route'=>route('admin.coupon.edit',$data->id),
        ]);  
        // $msg = 'Data Updated Successfully.';
        // return response()->json($msg);    
        //--- Redirect Section Ends           
    }
      //*** GET Request Status
      public function status($id1,$id2)
        {
            $data = Coupon::findOrFail($id1);
            $data->status = $id2;
            $data->update();
        }


    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Coupon::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        // $msg = 'Data Deleted Successfully.';
        // return response()->json($msg);      
        //--- Redirect Section Ends   
    }
}
