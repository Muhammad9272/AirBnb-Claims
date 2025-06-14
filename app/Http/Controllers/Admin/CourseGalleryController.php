<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseGallery;
use App\Models\Module;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class CourseGalleryController extends Controller
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
        $datas=CourseGallery::orderBy('id','desc')->get();  
        return DataTables::of($datas)
                             ->addIndexColumn()
                            ->addColumn('status', function(CourseGallery $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.course.gallery.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.course.gallery.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })
                            ->editColumn('module_id', function(CourseGallery $data) {
                                return $data->module?$data->module->name:'';
                            })

                            ->addColumn('action', function(CourseGallery $data) {
                                return '<div class="action-list">
                                
                                <a href="'.route('admin.course.gallery.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> 

                                <a data-href="' . route('admin.course.gallery.delete',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete</a>

                                </div>';
                            }) 
                            ->rawColumns(['status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side

       
    }

    public function index()
    {
        return view('admin.coursegallery.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $modules=Module::all();
        return view('admin.coursegallery.create',compact('modules'));
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
            'name' => 'required|unique:course_galleries,name',
            'photo'=>'required',
            'module_id'=>'required',
            'video' => 'required_without:video_type|max:50000',
            'video_link' => 'required_if:video_type,1',

        ]);


        $data=new CourseGallery();
        $data->name=$request->name;
        
        $data->module_id=$request->module_id;
        if ($file = $request->file('photo'))
        {
            $name = time().$file->getClientOriginalName();
             $name=str_replace(' ', '',$name);
            $file->move('assets/images/gallery/',$name);
            $data->photo = $name;
        }

        if (!$request->video_type && $file = $request->file('video'))
        {
            $name = time() . $file->getClientOriginalName();
            $name = str_replace(' ', '', $name);

            $disk = Storage::disk('s3');
            $disk->put('ABVideos/' . $name, file_get_contents($file), 'private');

            $data->video = $name;
        }if($request->video_type && $request->video_link){
             $data->video_link=$request->video_link;
        }
        $data->video_type=$request->video_type;
        
        $data->details=$request->details;
        $data->save();
        $data->slug= str_slug($data->name,'-');
        $data->update();
        


        // Session::flash('message', 'Data Added Successfully !');
        // Session::flash('alert-class', 'alert-success');
        toastr()->success('Data has been saved successfully!');
        return redirect()->route('admin.course.gallery.index');
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
        $data=CourseGallery::find($id);
        $modules=Module::all();
        return view('admin.coursegallery.edit',compact('data','modules'));
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
            'name' => 'required|unique:course_galleries,name,' . $id,
            'module_id'=>'required',
            'video_link' => 'required_if:video_type,1'
        ]);
        $data=CourseGallery::find($id);
        $data->name=$request->name;

        $data->module_id=$request->module_id;
        if ($file = $request->file('photo'))
        {
            $name = time().$file->getClientOriginalName();
             $name=str_replace(' ', '',$name);
            $file->move('assets/images/gallery/',$name);
            if($data->photo != null)
            {
                if (file_exists(public_path().'/assets/images/gallery/'.$data->photo)) {
                    unlink(public_path().'/assets/images/gallery/'.$data->photo);
                }
            }
            $data->photo = $name;
        }

        if (!$request->video_type && $file = $request->file('video'))
        {
            $name = time() . $file->getClientOriginalName();
            $name = str_replace(' ', '', $name);

            $disk = Storage::disk('s3');
            $disk->put('ABVideos/' . $name, file_get_contents($file), 'private');

            $oldVideo = $data->video;
            if (!empty($oldVideo)) {
                $disk->delete('ABVideos/' . $oldVideo);
            }

            $data->video = $name;
        }if ($request->video_type && $request->video_link){ 
             $data->video_link=$request->video_link;
        }
        $data->video_type=$request->video_type;

        $data->details=$request->details;
        $data->update();
        $data->slug= str_slug($data->name,'-');
        $data->update();

        toastr('Data has been updated successfully!', 'success');
        return redirect()->route('admin.course.gallery.index');
    }

        //*** GET Request
    public function status($id1,$id2)
    {
        $data = CourseGallery::findOrFail($id1);
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
        $data = CourseGallery::findOrFail($id);
        // delete video file from S3
        if ($data->video) {
            Storage::disk('s3')->delete('ABVideos/' . $data->video);
        }
        $data->delete();
    }
}
