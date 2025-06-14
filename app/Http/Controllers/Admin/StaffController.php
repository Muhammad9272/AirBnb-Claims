<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use DataTables;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
         $datas = User::where('role_type','admin')->where('id','!=',1)->where('id','!=',Auth::guard('admin')->user()->id)->orderBy('id')->get();
         //--- Integrating This Collection Into Datatables
         return DataTables::of($datas)
                            ->addColumn('role', function(User $data) {
                                $role = $data->role_id == 0 ? 'No Role' : ($data->role?$data->role->name:'');
                                return $role;
                            }) 
                             ->addColumn('action', function(User $data) {
                                return '<div class="action-list">
                                
                                <a href="'.route('admin.staff.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a>
                                 <a data-href="' . route('admin.staff.delete',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete</a>
                                </div>';
                            }) 
                            ->rawColumns(['action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.staff.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.staff.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        
       $request->validate([
        
         'email' => 'unique:users,email',
        ]);
        //--- Logic Section
        $data = new User();
        $input = $request->all();
        if ($file = $request->file('photo')) 
         {      
            $data->photo = Helpers::upload('admin/images/', config('fileformats.image'), $request->file('photo'));
        } 
        $data->role_type = 'admin';
        $data->role_id =$request->role_id;
        $input['password'] = bcrypt($request['password']);
        $data->fill($input)->save();
        //--- Logic Section Ends


        toastr()->success('Data has been saved successfully!');
        return redirect()->route('admin.staff.edit',$data->id);
 
    }


    public function edit($id)
    {
        $data = User::findOrFail($id);  
        return view('admin.staff.edit',compact('data'));
    }

    public function update(Request $request,$id)
    {
        //--- Validation Section
        if($id != Auth::guard('admin')->user()->id)
        {

           
            $request->validate([
             
             'email' => 'unique:users,email,'.$id
            ]);

            $input = $request->all();  
            $data = User::findOrFail($id);        
            if ($file = $request->file('photo')) 
            {              
               $data->photo = Helpers::update('admin/images/', $data->photo, config('fileformats.image'), $request->file('photo'));
            } 
            if($request->password == ''){
                $input['password'] = $data->password;
            }
            else{
                $input['password'] = bcrypt($request->password);
            }
            $data->update($input);
            $msg = 'Data Updated Successfully.';
            return redirect()->back()->with('success',$msg);   
        }
        else{
            $msg = 'You can not change your role.';
            return redirect()->back()->with('error',$msg);           
        }
 
    }

    //*** GET Request
    public function show($id)
    {
        $data = User::findOrFail($id);
        return view('admin.staff.show',compact('data'));
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        if($id == 1)
        {
            return "You don't have access to remove this admin";
        }
        $data = User::findOrFail($id);
        $data->delete();
        
    }
}
