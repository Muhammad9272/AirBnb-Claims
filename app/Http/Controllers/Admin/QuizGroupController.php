<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizBankGroup;
use Illuminate\Http\Request;

use App\CentralLogics\Helpers;
use App\Models\QuizBankManagement;
use DataTables;
class QuizGroupController extends Controller
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
        $datas=QuizBankGroup::orderBy('id','desc')->get();  
        return DataTables::of($datas)
                            ->addIndexColumn()
                            ->addColumn('company_id', function(QuizBankGroup $data) {
                                return $data->company?$data->company->name:'';
                            })

                            ->addColumn('status', function(QuizBankGroup $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.quiz.group.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.quiz.group.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })

                            ->addColumn('action', function(QuizBankGroup $data) {
                                return '<div class="action-list">
                                
                                <a href="'.route('admin.quiz.group.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> 


                                </div>';

                                // <a data-href="' . route('admin.quiz.group.delete',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete</a>
                            }) 
                            ->rawColumns(['name','banner','status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side

       
    }

    public function index()
    {
        return view('admin.quiz.groups.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $quizbankmanagement=QuizBankManagement::active()->latest()->first();
        return view('admin.quiz.groups.create',compact('quizbankmanagement'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'position' => 'required|string|max:255',
            'assessment_stage' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'details' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'subplan_ids' => 'required|array',
            'subplan_ids.*' => 'required|exists:sub_plans,id',
            'assessment_type' => 'required|string|max:255',
            'intro_video' => 'nullable|file|max:50000'
        ]);

        $data = new QuizBankGroup();
        $input=$request->all();
        // Upload intro video
        if ($request->hasFile('intro_video')) {
            $data->intro_video = Helpers::upload('quiz/intro_videos/', config('fileformats.video'), $request->file('intro_video'));
        }
        $subplanIds = $request->input('subplan_ids');
        $data->subplan_ids = json_encode($subplanIds);
        $data->slug=Helpers::slug($request->name);

        $data->fill($input)->save();
        toastr()->success('Data has been saved successfully!');
        return redirect()->route('admin.quiz.group.edit',$data->id);
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
        $quizbankmanagements=QuizBankManagement::active()->get();
        $data=QuizBankGroup::find($id);
        return view('admin.quiz.groups.edit',compact('data','quizbankmanagements'));
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'position' => 'required|string|max:255',
            'assessment_stage' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'details' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'subplan_ids' => 'required|array',
            'subplan_ids.*' => 'required|exists:sub_plans,id',
            'assessment_type' => 'required|string|max:255',
            'intro_video' => 'nullable|file|max:50000'
        ]);

        $data=QuizBankGroup::findOrfail($id);
        $input=$request->all();

        if ($request->hasFile('intro_video')) {
            $data->intro_video = Helpers::upload('quiz/intro_videos/', $data->intro_video, config('fileformats.video'), $request->file('intro_video'));
        }
        $subplanIds = $request->input('subplan_ids');
        $data->subplan_ids = json_encode($subplanIds);
        $data->slug=Helpers::slug($request->name);
        $data->fill($input)->update();

        toastr('Data has been updated successfully!', 'success');
        return redirect()->route('admin.quiz.group.edit',$data->id);
        
    }

        //*** GET Request
    public function status($id1,$id2)
    {
        $data = QuizBankGroup::findOrFail($id1);
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
        $data = QuizBankGroup::findOrFail($id);
        $data->delete();
    }
}