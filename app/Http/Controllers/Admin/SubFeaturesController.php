<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubFeature;
use Illuminate\Http\Request;
use Session;
use DataTables;
class SubFeaturesController extends Controller
{

    public function __construct(){

     $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatables()
    {   
        $datas=SubFeature::orderBy('id','desc')->get();  
        return DataTables::of($datas)
                             ->addIndexColumn()
                            ->addColumn('status', function(SubFeature $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.subfeature.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.subfeature.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })

                            ->addColumn('action', function(SubFeature $data) {
                                return '<div class="action-list">
                                
                                <a href="'.route('admin.subfeature.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> 

                                <a data-href="' . route('admin.subfeature.delete',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete</a>

                                </div>';
                            }) 
                            ->rawColumns(['status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side

       
    }

    public function index()
    {
        return view('admin.subfeatures.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.subfeatures.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        $this->validate($request,[
            'name'=>'required',
        ]);
        
         // Split the comma-separated names into an array
            $subfeatures = explode(',', $request->name);

            // Loop over each subfeature name and add it to the database
            foreach ($subfeatures as $subfeature) {
                $data = new SubFeature();
                $data->name = $subfeature;
                $data->save();
            }


        // Session::flash('message', 'Data Added Successfully !');
        // Session::flash('alert-class', 'alert-success');
        toastr()->success('Data has been saved successfully!');
        return redirect()->route('admin.subfeature.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=SubFeature::find($id);
        return view('admin.subfeatures.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $this->validate($request,[
            'name'=>'required',
        ]);
        $data=SubFeature::find($id);
        $input=$request->all();
        $data->update($input);

        // Session::flash('message', 'Data Updated Successfully !');
        // Session::flash('alert-class', 'alert-success');
        // return redirect()->back();
        // toastr()->success('');
        toastr('Data has been updated successfully!', 'success');
        return redirect()->route('admin.subfeature.index');
    }

        //*** GET Request
    public function status($id1,$id2)
    {
        $data = SubFeature::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = SubFeature::findOrFail($id);
        $data->delete();
    }
}
