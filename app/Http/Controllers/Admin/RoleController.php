<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use DataTables;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
         $datas = Role::orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return DataTables::of($datas)
                            ->addColumn('section', function(Role $data) {
                                $details =  str_replace('_',' ',$data->section);
                                $details =  ucwords($details);
                                return  '<div>'.$details.'</div>';
                            })
                            ->addColumn('action', function(Role $data) {
                                return '<div class="action-list"><a href="'.route('admin.role.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> </div>';
                            }) 
                            ->rawColumns(['section','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.role.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.role.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
       

        //--- Logic Section
        $data = new Role();
        $input = $request->all();
        if(!empty($request->section))
        {
            $input['section'] = implode(" , ",$request->section);
        }
        else{
            $input['section'] = '';
        }

        $data->fill($input)->save();
        //--- Logic Section Ends
      
        toastr()->success('Data has been saved successfully!');
        return redirect()->route('admin.role.edit',$data->id);
        //--- Redirect Section Ends    


    }

    //*** GET Request
    public function edit($id)
    {
        $data = Role::findOrFail($id);
        return view('admin.role.edit',compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        

        //--- Logic Section
        $data = Role::findOrFail($id);
        $input = $request->all();
        if(!empty($request->section))
        {
            $input['section'] = implode(" , ",$request->section);
        }
        else{
            $input['section'] = '';
        }
        $data->update($input);
        //--- Logic Section Ends

        toastr()->success('Data has been updated successfully!');
        return redirect()->route('admin.role.edit',$data->id);

    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Role::findOrFail($id);
        $data->delete();
      
  
    }
}
