<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use DataTables;
use Illuminate\Http\Request;
class CourseModulesController extends Controller
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
        $datas=Module::orderBy('id','desc')->get();  
        return DataTables::of($datas)
                             ->addIndexColumn()
                            ->addColumn('status', function(Module $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.course.modules.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.course.modules.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })
                            ->editColumn('course_id', function(Module $data) {
                                return $data->course?$data->course->name:'';
                            })

                            ->addColumn('action', function(Module $data) {
                                return '<div class="action-list">
                                
                                <a href="'.route('admin.course.modules.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> 

                                <a data-href="' . route('admin.course.modules.delete',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete</a>

                                </div>';
                            }) 
                            ->rawColumns(['status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side

       
    }

    public function index()
    {
        return view('admin.coursemodules.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $courses=Course::all();
        return view('admin.coursemodules.create',compact('courses'));
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
            'name' => 'required|unique:modules,name',
            'photo'=>'required',
            'course_id'=>'required',

        ]);
        $data=new Module();
        $data->name=$request->name;
        $data->course_id=$request->course_id;
        if ($file = $request->file('photo'))
        {
            $name = time().$file->getClientOriginalName();
             $name=str_replace(' ', '',$name);
            $file->move('assets/images/modules/',$name);
            $data->photo = $name;
        }

        $data->details=$request->details;
        $data->save();
        $data->slug= str_slug($data->name,'-');
        $data->update();

        // Session::flash('message', 'Data Added Successfully !');
        // Session::flash('alert-class', 'alert-success');
        toastr()->success('Data has been saved successfully!');
        return redirect()->route('admin.course.modules.index');
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
        $data=Module::find($id);
        $courses=Course::all();
        return view('admin.coursemodules.edit',compact('data','courses'));
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
            'name' => 'required|unique:modules,name,' . $id,
            'course_id'=>'required'
        ]);
        $data=Module::find($id);
        $data->name=$request->name;
        $data->course_id=$request->course_id;
        if ($file = $request->file('photo'))
        {
            $name = time().$file->getClientOriginalName();
             $name=str_replace(' ', '',$name);
            $file->move('assets/images/modules/',$name);
            if($data->photo != null)
            {
                if (file_exists(public_path().'/assets/images/modules/'.$data->photo)) {
                    unlink(public_path().'/assets/images/modules/'.$data->photo);
                }
            }
            $data->photo = $name;
        }

        $data->details=$request->details;
        $data->update();
        $data->slug= str_slug($data->name,'-');
        $data->update();

        // Session::flash('message', 'Data Updated Successfully !');
        // Session::flash('alert-class', 'alert-success');
        // return redirect()->back();
        // toastr()->success('');
        toastr('Data has been updated successfully!', 'success');
        return redirect()->route('admin.course.modules.index');
    }

        //*** GET Request
    public function status($id1,$id2)
    {
        $data = Module::findOrFail($id1);
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
        $data = Module::findOrFail($id);
        if($data->gallery->count() > 0)
        {
            //--- Redirect Section
            $msg = 'Remove Module Videos first !';
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        $data->delete();
    }
}
