<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobListing;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Validator;

class JobListingController extends Controller
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
        $datas=JobListing::orderBy('id','desc')->get();  
        return DataTables::of($datas)
                            ->addIndexColumn()
                            ->editColumn('company_id', function(JobListing $data) {
                                 return $data->company?$data->company->name:'';
                            })
                            ->editColumn('title', function (JobListing $data) {
                                $lastDate = $data->last_date ? Carbon::parse($data->last_date)->format('M d, Y') : '';
                                return '<div>
                                            <p>' . $data->title . '
                                            <small class="d-block"><span class="fw-bold">Created At : </span>' . $data->created_at->format('M d, Y') . '</small>
                                             <small class="d-block"><span class="fw-bold">Last Date : </span>' . $lastDate . '</small>
                                             <small class="d-block"><span class="fw-bold">Location : </span>' . $data->location . '</small>
                                             </p>
                                        </div>';
                            })

                            ->addColumn('status', function(JobListing $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.job.listings.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.job.listings.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })

                            ->addColumn('action', function(JobListing $data) {
                                return '<div class="action-list d-flex gap-1">
                                
                                <a href="'.route('admin.job.listings.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> 
                                <a data-href="' . route('admin.job.listings.delete',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete</a>

                                </div>';

                                // <a data-href="' . route('admin.job.listings.delete',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete</a>
                            }) 
                            ->rawColumns(['title','status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side

       
    }

    public function index()
    {
        return view('admin.jobs.joblistings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $companies=Company::active()->get();
        return view('admin.jobs.joblistings.create',compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        // validate the form data
        $rules=[
            'title' => 'required|string|max:255',
            'company_id'=>'required', 
            'service_line'=>'required',
            'job_link'=>'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data=new JobListing();
        $input=$request->all();

        $data->fill($input)->save();
        $data->slug=Helpers::slug($data->name.' '.$data->id);
        $data->update();
        // Session::flash('message', 'Data Added Successfully !');
        // Session::flash('alert-class', 'alert-success');
        return response()->json([
            'success' => true,
            'msg' => "Data has been saved successfully!",
            'route'=>route('admin.job.listings.create'),
        ]);

        // toastr()->success('Data has been saved successfully!');
        // return redirect()->route('admin.job.listings.edit',$data->id);
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
        $data=JobListing::find($id);
        $companies=Company::active()->get();
        return view('admin.jobs.joblistings.edit',compact('data','companies'));
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
        
        // validate the form data
        $rules=[
            'title' => 'required|string|max:255',
            'company_id'=>'required', 
            'service_line'=>'required',
            'job_link'=>'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data=JobListing::findOrfail($id);
        $input=$request->all();
        
        $data->slug=Helpers::slug($request->name.' '.$data->id);
        $data->fill($input)->update();


         return response()->json([
            'success' => true,
            'msg' => "Data has been updated successfully",
            'route'=>route('admin.job.listings.edit',$data->id),
        ]);

        // Session::flash('message', 'Data Added Successfully !');
        // Session::flash('alert-class', 'alert-success');
        // toastr('Data has been updated successfully!', 'success');
        // return redirect()->route('admin.job.listings.edit',$data->id);
        

    }

        //*** GET Request
    public function status($id1,$id2)
    {
        $data = JobListing::findOrFail($id1);
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
        $data = JobListing::findOrFail($id);     
        $data->delete();
    }
}