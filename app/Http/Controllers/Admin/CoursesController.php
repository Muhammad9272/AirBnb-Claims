<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use DataTables;
use Illuminate\Http\Request;
class CoursesController extends Controller
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
        $datas=Course::orderBy('id','desc')->get();  
        return DataTables::of($datas)
                             ->addIndexColumn()
                            ->addColumn('status', function(Course $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.courses.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.courses.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })

                            ->addColumn('action', function(Course $data) {
                                return '<div class="action-list">
                                
                                <a href="'.route('admin.courses.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> 

                                <a data-href="' . route('admin.courses.delete',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete</a>

                                </div>';
                            }) 
                            ->rawColumns(['status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side

       
    }

    public function index()
    {
        return view('admin.courses.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.courses.create');
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
            'name' => 'required|unique:courses,name',
            'photo'=>'required'

        ]);
        $data=new Course();
        $data->name=$request->name;
        if ($file = $request->file('photo'))
        {
            $name = time().$file->getClientOriginalName();
             $name=str_replace(' ', '',$name);
            $file->move('assets/images/courses/',$name);
            $data->photo = $name;
        }
        $data->author=$request->author;
        $data->details=$request->details;
        $data->save();
        $data->slug= str_slug($data->name,'-');
        $data->update();

        // Session::flash('message', 'Data Added Successfully !');
        // Session::flash('alert-class', 'alert-success');
        toastr()->success('Data has been saved successfully!');
        return redirect()->route('admin.courses.index');
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
        $data=Course::find($id);
        return view('admin.courses.edit',compact('data'));
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
            'name' => 'required|unique:courses,name,' . $id,
        ]);
        $data=Course::find($id);
        $data->name=$request->name;
        if ($file = $request->file('photo'))
        {
            $name = time().$file->getClientOriginalName();
             $name=str_replace(' ', '',$name);
            $file->move('assets/images/courses/',$name);
            if($data->photo != null)
            {
                if (file_exists(public_path().'/assets/images/courses/'.$data->photo)) {
                    unlink(public_path().'/assets/images/courses/'.$data->photo);
                }
            }
            $data->photo = $name;
        }
        $data->author=$request->author;
        $data->details=$request->details;
        $data->update();
        $data->slug= str_slug($data->name,'-');
        $data->update();

        // Session::flash('message', 'Data Updated Successfully !');
        // Session::flash('alert-class', 'alert-success');
        // return redirect()->back();
        // toastr()->success('');
        toastr('Data has been updated successfully!', 'success');
        return redirect()->route('admin.courses.index');
    }

        //*** GET Request
    public function status($id1,$id2)
    {
        $data = Course::findOrFail($id1);
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
        $data = Course::findOrFail($id);

        if($data->modules->count() > 0)
        {
            //--- Redirect Section
            $msg = 'Remove Course Modules first !';
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        $data->delete();

    }
}
