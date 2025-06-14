<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\QuizBank;
use App\Models\QuizBankManagement;
use App\Models\SubPlan;
use DataTables;
use Session;
use Illuminate\Http\Request;
use DB;
use Validator;
class QuizBankController extends Controller
{

    public function __construct(){
     $this->middleware('auth:admin');
     $this->fileformats=config('fileformats.image') . '|' . config('fileformats.video');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatables($quizmanagement_slug=null)
    {   
        if($quizmanagement_slug){
            $quizmanagement=QuizBankManagement::where('slug',$quizmanagement_slug)->first();
            $datas=$quizmanagement->quizBanks()->latest()->get(); 
        }
        else{
          $datas=QuizBank::latest()->get(); 
        }
        return DataTables::of($datas)
                           ->addColumn('select', function(QuizBank $data) {
                                return 
                                '<div class=""><input type="checkbox" class="checkbox form-check-input sub_select" name="" data-id="'.$data->id.'"></div>';

                            })
                            ->addColumn('status', function(QuizBank $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.quizbank.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.quizbank.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })

                            ->addColumn('action', function(QuizBank $data) {
                                return '<div class="action-list">
                                
                                <a href="'.route('admin.quizbank.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> 


                                </div>';

                                // <a data-href="' . route('admin.quizbank.delete',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete</a>
                            }) 
                            ->rawColumns(['select','status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side

       
    }

    public function index($quizmanagement_slug=null)
    {
        $quizmanagement=QuizBankManagement::where('slug',$quizmanagement_slug)->firstOrFail();
        return view('admin.quiz.quizbank.index',compact('quizmanagement','quizmanagement_slug'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($quizmanagement_slug)
    {   

        $quizbankmanagement=QuizBankManagement::where('slug',$quizmanagement_slug)->first();
        return view('admin.quiz.quizbank.create',compact('quizbankmanagement'));
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
            'quizbankmanagement_id' => 'required|exists:quiz_bank_management,id',
            'quiz_group' => 'required|string|max:255',
            'question_type' => 'required|string|max:255',
            'details' => 'nullable|string',
            'game_id' => 'required_if:question_type,Game-based',
            'suggested_answer' => 'nullable|string',
            'prepare_time' => 'nullable|string|max:255',
            'response_time' => 'nullable|string|max:255',
            // 'promotion_photo' => 'nullable|file|max:50000',
            'promotion_link' => 'nullable|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
      
        $data = new QuizBank();
        $input=$request->all();
        // Upload  video
        if ($request->hasFile('promotion_media')) {
            $data->promotion_media = Helpers::upload('quiz/gallery/',$this->fileformats, $request->file('promotion_media'));
        }

        if($request->gallery){
            $gallery=[];
            foreach($request->gallery as $photo){ 
                $file = new \Illuminate\Http\UploadedFile( storage_path('tmp/uploads/'.$photo), $photo,null,null, true);  
                $gallery[] = Helpers::upload('/quiz/gallery/',$this->fileformats , $file);
     
            }
            $data->gallery = json_encode($gallery);
        } 


        if ($request->has('pdfs') && $request->pdfs!='[]') {
            // Upload  video
            if ($request->hasFile('pdfFile')) {
                $data->pdf_file = Helpers::upload('quiz/pdf_files/',config('fileformats.pdfs'), $request->file('pdfFile'));
            }

            $pdfImagePaths = [];
            foreach ($request->pdfs as $key=>$base64Image) {
                $base64Image = str_replace('data:image/jpeg;base64,', '', $base64Image);
                $base64Image = str_replace(' ', '+', $base64Image);

                $decodedImage = base64_decode($base64Image);
                
                // Create a temporary file
                $tempFilePath = tempnam(sys_get_temp_dir(), 'pdf_image_');
                file_put_contents($tempFilePath, $decodedImage);
                
                // Convert temporary file to UploadedFile instance
                $file = new  \Illuminate\Http\UploadedFile($tempFilePath, 'pdf_image_' . $key.time() . '.png', 'image/png', null, true);
                
                // Use the helper function to store the file
                $filename = Helpers::upload('quiz/pdf_images', $this->fileformats, $file);
                
                // Store the path or filename (as per your requirement) in the array
                $pdfImagePaths[] = $filename;
                
                // Clean up temporary file
                @unlink($tempFilePath);
            }
            $data->pdf_images = json_encode($pdfImagePaths);
        }


        // if ($request->has('pdfs')) {
        //     // Upload the PDF file itself
        //     if ($request->hasFile('pdfFile')) {
        //         $data->pdf_file = Helpers::upload('quiz/pdf_files/', config('fileformats.pdfs'), $request->file('pdfFile'));
        //     }

        //     $pdfImagePaths = [];

        //     // Get the files from the 'pdfs[]' input
        //     $imageFiles = $request->file('pdfs');
        //     foreach ($imageFiles as $key => $file) {
        //         // Use the helper function to store the file
        //         $filename = Helpers::upload('quiz/pdf_images', $this->fileformats, $file);
                
        //         // Store the path or filename (as per your requirement) in the array
        //         $pdfImagePaths[] = $filename;
        //     }

        //     $data->pdf_images = json_encode($pdfImagePaths);
        // }

        
        $data->options=$request->options?json_encode($request->options):null;
        $data->fill($input)->save();

        // $quizGroupInitials = implode('', array_map(fn($word) => $word[0], explode(' ', $data->quiz_group)));

        // $questType = substr($data->question_type, 0, 2);
        $data->slug = Helpers::quizSlug($data->quiz_group,$data->id);

        $data->update();

        $quizbankmanagement=QuizBankManagement::find($data->quizbankmanagement_id);

        return response()->json([
            'success' => true,
            'msg' => "Data has been saved successfully!",
            'route'=>route('admin.quizbank.create',$quizbankmanagement->slug),
        ]);

        // toastr()->success('Data has been saved successfully!');
        // return redirect()->route('admin.quizbank.create',$quizbankmanagement->slug);
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
        
        $data=QuizBank::find($id);
        $quizbankmanagement=QuizBankManagement::where('id',$data->quizbankmanagement_id)->first();
        return view('admin.quiz.quizbank.edit',compact('data','quizbankmanagement'));
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
           'quizbankmanagement_id' => 'required|exists:quiz_bank_management,id',
            'quiz_group' => 'required|string|max:255',
            'question_type' => 'required|string|max:255',
            'details' => 'nullable|string',
            'game_id' => 'required_if:question_type,Game-based',
            // 'pdfFile' => 'required_if:question_type,Pdf-based',

            'suggested_answer' => 'nullable|string',
            'prepare_time' => 'nullable|string|max:255',
            'response_time' => 'nullable|string|max:255',
            // 'promotion_photo' => 'nullable|file|max:50000',
            'promotion_link' => 'nullable|string|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data=QuizBank::findOrfail($id);
        $input=$request->all();
        

         // Upload  video
        if ($request->hasFile('promotion_media')) {
            $data->promotion_media = Helpers::update('quiz/gallery/',$data->promotion_media, $this->fileformats, $request->file('promotion_media'));
        }

        $gallery=[];
        if(!empty($data->gallery) && !is_null($data->gallery)){
            foreach (json_decode($data->gallery) as  $key => $file){

                if( !is_null($request->old) && in_array($file, $request->old) ){
                    $gallery[]=$file;
                }
                else{         
                    Helpers::unlink('/quiz/gallery/', $file); 
                }
            }
        }
        if($request->gallery){
            foreach($request->gallery as $photo){ 
                $file = new \Illuminate\Http\UploadedFile( storage_path('tmp/uploads/'.$photo), $photo,null,null, true);
                $gallery[] = Helpers::upload('/quiz/gallery/',$this->fileformats , $file);    
            }                
        } 
        
         $gallerydata = json_encode($gallery); 
         if ($gallerydata == "[]") {  
              $data->gallery=null;
         }else{
             $data->external_gallery=null;
             $data->gallery=$gallerydata;
         }        
        
        if ($request->has('pdfs') && $request->pdfs!='[]') {
            // Upload  video
            if ($request->hasFile('pdfFile')) {
                $data->pdf_file = Helpers::upload('quiz/pdf_files/',config('fileformats.pdfs'), $request->file('pdfFile'));
            }

            $pdfImagePaths = [];
            foreach ($request->pdfs as $key=>$base64Image) {
                $base64Image = str_replace('data:image/jpeg;base64,', '', $base64Image);
                $base64Image = str_replace(' ', '+', $base64Image);

                $decodedImage = base64_decode($base64Image);
                
                // Create a temporary file
                $tempFilePath = tempnam(sys_get_temp_dir(), 'pdf_image_');
                file_put_contents($tempFilePath, $decodedImage);
                
                // Convert temporary file to UploadedFile instance
                $file = new  \Illuminate\Http\UploadedFile($tempFilePath, 'pdf_image_' . $key.time() . '.png', 'image/png', null, true);
                
                // Use the helper function to store the file
                $filename = Helpers::upload('quiz/pdf_images', $this->fileformats, $file);
                
                // Store the path or filename (as per your requirement) in the array
                $pdfImagePaths[] = $filename;
                
                // Clean up temporary file
                @unlink($tempFilePath);
            }
            $data->pdf_images = json_encode($pdfImagePaths);
        }
        
        $data->options=$request->options?json_encode($request->options):null;
        
        $data->slug = Helpers::quizSlug($data->quiz_group,$data->id);

        $data->fill($input)->update();

        return response()->json([
            'success' => true,
            'msg' => "Data has been updated successfully",
            'route'=>route('admin.quizbank.edit',$data->id),
        ]);

        // toastr('Data has been updated successfully!', 'success');
        // return redirect()->route('admin.quizbank.edit',$data->id);
        
    }

        //*** GET Request
    public function status($id1,$id2)
    {
        $data = QuizBank::findOrFail($id1);
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
        $data = QuizBank::findOrFail($id);
        $data->delete();
    }

    public function createImport($value='')
    {  
       $quizbankmanagements=QuizBankManagement::active()->get();
       return view('admin.quiz.quizbank.import',compact('quizbankmanagements'));
    }

    public function importSubmit(Request $request)
    {  
        if(!$request->quizbankmanagement_id){
            return redirect()->back()->with('error','Please select QuizManagement');
        }
        $quizbankmanagement=QuizBankManagement::where('id',$request->quizbankmanagement_id)->firstOrFail(); 
        $log = "";
        //--- Validation Section
         $this->validate($request,[
            'csvfile'      => 'required|mimes:csv,txt',
        ]);

        $filename = '';
        if ($file = $request->file('csvfile')) {
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->move('assets/temp_files', $filename);
        }

        $datas = "";

        $file = fopen(public_path('assets/temp_files/' . $filename), "r");
        $i = 1;
        while (($row = fgetcsv($file)) !== FALSE) {

            if ($i != 1) {

                if (!QuizBank::where('sku',$row[0])->exists()){
                    //--- Logic Section
                    $data = new QuizBank;
                    
                    $data->sku = $row[0];
                    $data->quiz_group = $row[1];

                    // Check if the group name exists in QuizBankManagement
                    if (!strpos($quizbankmanagement->quiz_group_names, $row[1])) {
                        // If the group name does not exist, add it to the quiz_group_names field and save the QuizBankManagement object
                        $quizbankmanagement->quiz_group_names .= ',' . $row[1];
                        $quizbankmanagement->update();
                    }


                    $data->question_type = $row[2];
                    $data->game_id = trim($row[3]);
                    $data->title = $row[4];

                    $data->quizbankmanagement_id = $quizbankmanagement->id;

                    // if($external_gallery=$row[4]){
                    //     $external_gallery = explode(',',$external_gallery);
                    //     $data->external_gallery = json_encode($external_gallery);
                    // }
                    
                    $data->details = $row[5];
                   
                    if($options=$row[6]){
                     $options = explode(',',$options);
                     $data->options = json_encode($options);
                    }
                    
                    $data->suggested_answer = $row[7];
                    $data->prepare_time = $row[8];
                    $data->response_time = $row[9];
                    // $data->promotion_media = $row[10];
                    $data->promotion_link = $row[10];

                    // Save Data
                    $data->save();
                    $data->slug = Helpers::quizSlug($data->quiz_group,$data->id);

                    $data->update();
                     
                } else {
                    $log .= "<br>Row No: " . $i . " - Duplicate sku rows!<br>";
                }
            }
            $i++;
        }
        fclose($file);
        //--- Redirect Section
        $msg = 'Quiz Bank Imported Successfully.<a class="text-primary" href="' . route('admin.quizbank.index',$quizbankmanagement->slug) . '">View Quiz Bank.</a>' . $log;
        Session::flash('message', $msg);
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
       
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
                    DB::table('quiz_banks')->whereIn('id', $ids)->update(['status' => $request->input('mb_status')]);
                    break;

                // case 'mb_change_price':
                //     QuizBankManagement::whereIn('id', $ids)->update(['price' => $request->mb_price]);
                //     break;

                // case 'mb_membership_level':
                //     $subplanIds = $request->input('subplan_ids');
                //     $subplanIdss=$subplanIds? json_encode($subplanIds):null;
                //     QuizBankManagement::whereIn('id', $ids)->update(['subplan_ids' => $subplanIdss]);
                //     break;

                // case 'mb_assessment_type':
                //     QuizBank::whereIn('id', $ids)->update(['assessment_type' => $request->assessment_type]);
                //     break;

                case 'mb_delete_products':
                      QuizBank::whereIn('id', $ids)->delete();                   
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

}