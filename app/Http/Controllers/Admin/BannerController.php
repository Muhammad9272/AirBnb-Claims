<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use DataTables;
class BannerController extends Controller
{

    public function __construct(){

     $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatables(Request $request)
    {   
        if($request->file_type){
             $datas=Banner::where('for_section','document')->orderBy('id','asc')->get();
        }else{
             $datas=Banner::whereNot('for_section','document')->orderBy('id','asc')->get();
        }        
        return DataTables::of($datas)
                             ->addIndexColumn()
                            ->editColumn('for_section', function(Banner $data) {
                                      $title=$data->for_section=='document'?'<p class="mb-0">Title: '.$data->title.'</p>':'';
                                      return '<p class="mb-0">'.ucwords(str_replace('_', ' ', $data->for_section)).
                                       $title;

                            })
                            ->addColumn('status', function(Banner $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.banner.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.banner.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })

                            ->addColumn('action', function(Banner $data) {
                                return '<div class="action-list">
                                
                                <a href="'.route('admin.banner.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> 

                               

                                </div>';
                                 // <a data-href="' . route('admin.banner.delete',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete</a>
                            }) 
                            ->rawColumns(['for_section','status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side

       
    }

    public function index($file_type=null)
    {   
        return view('admin.banners.index',compact('file_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $for_section='document';
        return view('admin.banners.create',compact('for_section'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        // Retrieve form data
        $bannerData = $request->only([
            'title',
            'details',
            'photo',
            'photo_link',
            'video',
        ]);

        $data=new Banner();       
        if ($request->hasFile('file')) {
            try{
                $data->file= Helpers::upload('banners/', config('fileformats.document'), $request->file('file'));
            }catch(\Exception $e){
                return back()->with('error',$e->getMessage());
            }
            
        }
        $data->file_link=$request->file_link;
        try{
        $data->video = Helpers::upload('banners/video/',config('fileformats.video'), $request->file('video'));
        }catch(\Exception $e){
                return back()->with('error',$e->getMessage());
        }
        $data->title=$request->title;
        $data->details=$request->details;        
        $data->save();

        toastr()->success('Data has been saved successfully!');
        return redirect()->route('admin.banner.edit',$data->id);
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
        $data=Banner::find($id);
        return view('admin.banners.edit',compact('data'));
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

        // Retrieve form data
        $bannerData = $request->only([
            'title',
            'details',
            'photo',
            'photo_link',
            'video',
        ]);
        $data=Banner::find($id);    
        
        if ($request->hasFile('file')) {
            $file_type=$data->for_section=="document"?config('fileformats.document'):config('fileformats.image');
            try{
              $data->file=Helpers::update('banners/',$data->file, $file_type, $request->file);
            }catch(\Exception $e){
                return back()->with('error',$e->getMessage());
            }
        }
        if ($request->hasFile('video')) {
             try{
                $data->video = Helpers::update('banners/video/',$data->video, config('fileformats.video'), $request->file('video'));
             }catch(\Exception $e){
                return back()->with('error',$e->getMessage());
             }
        }
        $data->file_link=$request->file_link;
        $data->title=$request->title;
        $data->details=$request->details;  

        $data->update();

       
        toastr('Data has been updated successfully!', 'success');
        return redirect()->back();
    }

        //*** GET Request
    public function status($id1,$id2)
    {
        $data = Banner::findOrFail($id1);
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
        $data = Banner::findOrFail($id);

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
