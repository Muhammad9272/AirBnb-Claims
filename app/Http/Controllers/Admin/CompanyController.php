<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Company;
use DataTables;
use Illuminate\Http\Request;
use Validator;
class CompanyController extends Controller
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
        $datas=Company::orderBy('id','desc')->get();  
        return DataTables::of($datas)
                            ->addIndexColumn()
                            ->addColumn('name', function(Company $data) {
                                return '<div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm bg-light rounded p-1"><img src="'.Helpers::image($data->logo, 'company/logo/').'" alt=""
                                                class="img-fluid d-block"></div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fs-14 mb-1">'.$data->name.'</h5>
                                        <p class="text-muted mb-0"> <span class="fw-medium">'.$data->small_description.'</span></p>
                                    </div>
                                </div>';
                            })
                            // ->addColumn('banner', function(Company $data) {

                            //   return '<div class="avatar-sm1 bg-light rounded p-1" width="250px">
                            //               <img src="'.Helpers::image($data->banner, 'company/banner/').'" alt=""
                            //                     class="img-fluid d-block">
                            //         </div>';
                            // })
                            ->addColumn('status', function(Company $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.company.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.company.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })

                            ->addColumn('action', function(Company $data) {
                                return '<div class="action-list">
                                
                                <a href="'.route('admin.company.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> 


                                </div>';

                                // <a data-href="' . route('admin.company.delete',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete</a>
                            }) 
                            ->rawColumns(['name','banner','status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side

       
    }

    public function index()
    {
        return view('admin.company.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        // dd($request->all());
        // validate the form data
        $rules=[
            'name' => 'required|string|max:255|unique:companies,name',
            // 'logo' => 'required',
            // 'banner' => 'nullable|image|max:2048',
            'small_description'=>'required|string|max:255',
            'details' => 'required',
            'application_process' => 'nullable|string',
            'sample_question_ids' => 'nullable|array',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data=new Company();
        $input=$request->all();

           $data->logo = Helpers::upload('company/logo/',config('fileformats.image'), $request->file('logo'));
           $data->banner = Helpers::upload('company/banner/',config('fileformats.image'), $request->file('banner'));

           // $data->sample_question_ids=json_encode($request->sample_question_ids);
           $data->slug=Helpers::slug($request->name);

        $data->fill($input)->save();

        // Session::flash('message', 'Data Added Successfully !');
        // Session::flash('alert-class', 'alert-success');
        return response()->json([
            'success' => true,
            'msg' => "Data has been saved successfully!",
            'route'=>route('admin.company.edit',$data->id),
        ]);

        // toastr()->success('Data has been saved successfully!');
        // return redirect()->route('admin.company.edit',$data->id);
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
        $data=Company::find($id);
        $quizbanks = $data->quizBankManagements()->active()->with('quizBanks')->get()->pluck('quizBanks')->flatten()->where('status', true);
        return view('admin.company.edit',compact('data','quizbanks'));
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
        // dd($request->all());
        // validate the form data
        $rules=[
            'name' => 'required|string|max:255|unique:companies,name,'.$id,
            // 'logo' => 'required',
            // 'banner' => 'nullable|image|max:2048',
            'small_description'=>'required|string|max:255',
            'details' => 'required',
            'application_process' => 'nullable|string',
            'sample_question_ids' => 'nullable|array',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data=Company::findOrfail($id);
        $input=$request->all();

           $data->logo = Helpers::update('company/logo/', $data->logo,config('fileformats.image'), $request->file('logo'));
           $data->banner = Helpers::update('company/banner/', $data->banner,config('fileformats.image'), $request->file('banner'));

           $data->sample_question_ids=$request->sample_question_ids?json_encode($request->sample_question_ids):null;
           $data->slug=Helpers::slug($request->name);

        $data->fill($input)->update();

         return response()->json([
            'success' => true,
            'msg' => "Data has been updated successfully",
            'route'=>route('admin.company.edit',$data->id),
        ]);

        // Session::flash('message', 'Data Added Successfully !');
        // Session::flash('alert-class', 'alert-success');
        // toastr('Data has been updated successfully!', 'success');
        // return redirect()->route('admin.company.edit',$data->id);
        

    }

        //*** GET Request
    public function status($id1,$id2)
    {
        $data = Company::findOrFail($id1);
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
        $data = Company::findOrFail($id);     
        if($data->quizBankManagements){
            return redirect()->back('error','Delete Quizmanagement First');
        }
        $data->favorites()->delete();

        // Delete associated job listings
        foreach ($company->jobListings as $jobListing) {
            $jobListing->delete();
        }


        $data->delete();
    }
}