<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\QuizBank;
use App\Models\QuizBankManagement;
use App\Models\QuizProgress;
use App\Models\SubPlan;
use App\Models\UserQuizBankAccess;
use DB;
use DataTables;
use Illuminate\Http\Request;
use Validator;
class QuizManagementController extends Controller
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
        $datas=QuizBankManagement::orderBy('id','desc')->get();  
        return DataTables::of($datas)
                            // ->addIndexColumn()                            
                            ->addColumn('select', function(QuizBankManagement $data) {
                                return 
                                '<div class=""><input type="checkbox" class="checkbox form-check-input sub_select" name="" data-id="'.$data->id.'"></div>';

                            })

                            ->addColumn('company_id', function(QuizBankManagement $data) {
                                return $data->company?$data->company->name:'';
                            })

                            ->addColumn('status', function(QuizBankManagement $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.quiz.management.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.quiz.management.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })

                            ->addColumn('action', function(QuizBankManagement $data) {
                                return '<div class="action-list">
                                
                                 <a data-href="' . route('admin.quiz.management.delete',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light mb-1"><i class="ri-delete-bin-fill align-middle fs-16 me-2 "></i>Delete</a>

                                <a href="'.route('admin.quiz.management.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light mb-1"><i class="ri-edit-box-fill align-middle fs-16 me-2 "></i>Edit</a>

                                <a href="'.route('admin.quizbank.index',$data->slug).'" class="btn btn-primary btn-sm fs-13 waves-effect waves-light mb-1"><i class="ri-eye-fill align-middle fs-16 me-2 "></i>View QuizBank</a> 

                                  <a href="'.route('admin.quizbank.create',$data->slug).'" class="btn btn-success btn-sm fs-13 waves-effect waves-light"><i class="ri-add-line  align-middle fs-16 me-2"></i>Create QuizBank</a>
                                <a href="'.route('admin.quiz.management.duplicate',$data->id).'" class="btn btn-success btn-sm fs-13 waves-effect waves-light"><i class="ri-file-copy-fill align-middle fs-16 me-2"></i>Duplicate QuizBank</a>

                                </div>';

                               
                            }) 
                            ->rawColumns(['name','select','banner','status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side

       
    }

    public function index()
    {   
        $subplans=SubPlan::active()->get();
        return view('admin.quiz.management.index',compact('subplans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $companies=Company::active()->get();
        $subplans=SubPlan::active()->get();
        return view('admin.quiz.management.create',compact('companies','subplans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $rules=[
            'name' => 'required|string|max:255|unique:quiz_bank_management,name',
            'company_id' => 'required|exists:companies,id',
            'position' => 'required|string|max:255',
            'assessment_stage' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'details' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'details'=>'required',
            'quiz_group_names'=>'required',
            // 'subplan_ids' => 'required|array',
            // 'subplan_ids.*' => 'required|exists:sub_plans,id',
            'assessment_type' => 'required|string|max:255',
            'intro_video' => 'nullable|file|max:50000'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = new QuizBankManagement();
        $input=$request->all();
        // Upload intro video
        if ($request->hasFile('intro_video')) {
            $data->intro_video = Helpers::upload('quiz/intro_videos/', config('fileformats.video'), $request->file('intro_video'));
        }
        $subplanIds = $request->input('subplan_ids');
        $data->subplan_ids =$subplanIds? json_encode($subplanIds):null;
        $data->slug=Helpers::slug($request->name);

        $data->fill($input)->save();

        return response()->json([
            'success' => true,
            'msg' => "Data has been saved successfully!",
            'route'=>route('admin.quizbank.create',$data->slug),
        ]);
        

        // toastr()->success('Data has been saved successfully!');
        // return redirect()->route('admin.quizbank.create',$data->slug);
        // return redirect()->route('admin.quiz.management.edit',$data->id);
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
        $companies=Company::active()->get();
        $subplans=SubPlan::active()->get();
        $data=QuizBankManagement::find($id);
        return view('admin.quiz.management.edit',compact('data','companies','subplans'));
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
        $rules=[
            'name' => 'required|string|max:255|unique:quiz_bank_management,name,'.$id,
            'company_id' => 'required|exists:companies,id',
            'position' => 'required|string|max:255',
            'assessment_stage' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'details' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'details'=>'required',
            'quiz_group_names'=>'required',
            // 'subplan_ids' => 'required|array',
            // 'subplan_ids.*' => 'required|exists:sub_plans,id',
            'assessment_type' => 'required|string|max:255',
            'intro_video' => 'nullable|file|max:50000'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data=QuizBankManagement::findOrfail($id);
        $input=$request->all();

        if($data->position!=$request->position){
            $positionsdata=UserQuizBankAccess::where('quiz_bank_management_id',$data->id)
                              ->where('position',$data->position)
                              ->update(['position' => $request->position]);
        }


        if ($request->hasFile('intro_video')) {
            $data->intro_video = Helpers::update('quiz/intro_videos/', $data->intro_video, config('fileformats.video'), $request->file('intro_video'));
        }
        $subplanIds = $request->input('subplan_ids');
        $data->subplan_ids =$subplanIds? json_encode($subplanIds):null;
        $data->slug=Helpers::slug($request->name);
        $data->fill($input)->update();

        return response()->json([
            'success' => true,
            'msg' => "Data has been updated successfully",
            'route'=>route('admin.quiz.management.edit',$data->id),
        ]);


        // toastr('Data has been updated successfully!', 'success');
        // return redirect()->route('admin.quiz.management.edit',$data->id);
        
    }

        //*** GET Request
    public function status($id1,$id2)
    {
        $data = QuizBankManagement::findOrFail($id1);
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
        $data = QuizBankManagement::findOrFail($id);
        // Delete the associated favorites
        $data->favorites()->delete();
        
        $quizzes = QuizBank::where('quizbankmanagement_id', $data->id)->get();
        $accesses=UserQuizBankAccess::where('quiz_bank_management_id', $data->id)->get();
        // Duplicate the associated quizzes
        foreach ($quizzes as $quiz) {
           QuizProgress::where('quiz_id',$quiz->id)->delete();
           $quiz->delete();
        }
        foreach($accesses as $access){
            $access->delete();
        }
        $data->delete();
    }



    public function bulkaction(Request $request)
    {
        $selectedIds = $request->input('selectedrowsIds');
        $action = $request->input('bulkactions');

        if(empty($selectedIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No rows selected'
            ]);
        }

        $ids = explode(',', $selectedIds);

        DB::beginTransaction();

        try {
            switch ($action) {
                case 'mb_change_status':
                    DB::table('quiz_bank_management')->whereIn('id', $ids)->update(['status' => $request->input('mb_status')]);
                    break;

                case 'mb_change_price':
                    QuizBankManagement::whereIn('id', $ids)->update(['price' => $request->mb_price]);
                    break;

                case 'mb_membership_level':
                    $subplanIds = $request->input('subplan_ids');
                    $subplanIdss=$subplanIds? json_encode($subplanIds):null;
                    QuizBankManagement::whereIn('id', $ids)->update(['subplan_ids' => $subplanIdss]);
                    break;

                case 'mb_assessment_type':
                    QuizBankManagement::whereIn('id', $ids)->update(['assessment_type' => $request->assessment_type]);
                    break;

                case 'mb_delete_products':
                      // Product::whereIn('id', $ids)->where('user_id', auth()->id())->delete();                   
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid action'
                    ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Bulk action completed successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error occurred: ' . $e->getMessage()
            ]);
        }
    }



    public function duplicateQuizManagement($id)
    {
        // Find the original quiz management record
        $originalQuizManagement = QuizBankManagement::findOrFail($id);

        // Create the initial duplicated name with the '(Copy)' suffix
        $duplicatedName = $originalQuizManagement->name . ' (Copy)';
        // Check if the name is already used, and increment the copy count until a unique name is found
        $copyCount = 1;
        while (QuizBankManagement::where('name', $duplicatedName)->exists()) {
            $duplicatedName = $originalQuizManagement->name . ' (Copy ' . $copyCount . ')';
            $copyCount++;
        }


        // Duplicate the quiz management record
        $duplicatedQuizManagement = $originalQuizManagement->replicate();
        $duplicatedQuizManagement->name = $duplicatedName; // Modify the name to indicate it's a copy
        $duplicatedQuizManagement->slug = Helpers::slug($duplicatedName);

        if ($duplicatedQuizManagement->intro_video) {
            $duplicatedQuizManagement->intro_video = Helpers::duplicateFile($duplicatedQuizManagement->intro_video,'quiz/intro_videos/');
        }

        $duplicatedQuizManagement->save();
        
        $quizzes = QuizBank::where('quizbankmanagement_id', $originalQuizManagement->id)->get();

        // Duplicate the associated quizzes
        foreach ($quizzes as $originalQuiz) {

            $duplicatedQuiz = $originalQuiz->replicate();
            $duplicatedQuiz->quizbankmanagement_id = $duplicatedQuizManagement->id;

            // Duplicate the quiz's gallery if it exists
            if ($originalQuiz->gallery) {
                $gallery = json_decode($originalQuiz->gallery);
                $duplicatedGallery = [];
                foreach ($gallery as $photo) {
                    $duplicatedGallery[] = Helpers::duplicateFile($photo, 'quiz/gallery/');
                }
                $duplicatedQuiz->gallery = json_encode($duplicatedGallery);
            }
            // Duplicate the quiz's promotion media if it exists
            if ($originalQuiz->promotion_media) {
                $duplicatedQuiz->promotion_media = Helpers::duplicateFile($originalQuiz->promotion_media, 'quiz/gallery/');
            }


            $duplicatedQuiz->save();

            $duplicatedQuiz->slug = Helpers::quizSlug($duplicatedQuiz->quiz_group,$duplicatedQuiz->id);

            $duplicatedQuiz->update();
        }
        
        return redirect()->back()->with('success','Data Duplicated successfully');
        // Redirect to the edit page of the duplicated quiz management
        //return redirect()->route('admin.quizbank.create', $duplicatedQuizManagement->slug);
    }



}