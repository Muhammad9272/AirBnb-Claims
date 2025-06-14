<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\CareerEvent;
use App\Models\CareerEventRegistration;
use App\Models\GeneralSetting;
use App\Models\SubPlan;
use Carbon\Carbon;
use DB;
use DataTables;
use Illuminate\Http\Request;
class CareerEventRegistrationController extends Controller
{
    public function __construct(GeneralSetting $settings){
    $this->settings = $settings::first();
     $this->middleware('auth:admin');
    }


    public function datatables($id='')
    {   
        if(!$id){
            return;
        }
        $datas=CareerEventRegistration::where('event_id',$id)->orderBy('id','desc')->get();
        return DataTables::of($datas)
                            // ->addIndexColumn()
                            ->addColumn('affiliate_code', function(CareerEventRegistration $data) {
                                 return $data->user?$data->user->affiliate_code:'';
                            })
                            ->addColumn('user_details', function(CareerEventRegistration $data) {
                                return '<div>
                                    <p class="mb-0">Name : '.($data->user?$data->user->name:'').'</p>
                                    <p class="mb-0"><small>Email : '.($data->user?$data->user->email:'').'</small></p>
                                    <p class="mb-0"><small>Phone : '.($data->user?$data->user->phone:'').'</small></p>
                                </div>'; 
                            })
                           
                            
                            ->editColumn('created_at', function(CareerEventRegistration $data) {
                                return $data->created_at->format('F d, Y');
                            })
                            
                            ->addColumn('action', function(CareerEventRegistration $data) {
                                if($data->user)
                                return '<div class="action-list">
                                 <a href="'.route('admin.users.show',$data->user->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-eye-fill align-middle fs-16 me-2"></i>View</a> 
                                </div>';
                            }) 
                            ->rawColumns(['user_details','created_at','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
      
    }
    public function index($id='')
    {   
        if($id){
            $data=CareerEvent::findOrfail($id); 
            return view('admin.careerevents.registered-users.index',compact('id','data'));
        }
    }

}