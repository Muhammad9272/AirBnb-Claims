<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Coupon;
use App\Models\GeneralSetting;
use App\Models\SubPlan;
use App\Models\User;
use DB;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class TutorController extends Controller
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
        $datas=User::user()->where('role_id', 2)->orderBy('id', 'desc')->get();  
        return DataTables::of($datas)
                            // ->addIndexColumn()                            
                            ->addColumn('select', function(User $data) {
                                return 
                                '<div class=""><input type="checkbox" class="checkbox form-check-input sub_select" name="" data-id="'.$data->id.'"></div>';

                            })
                            ->addColumn('name', function(User $data) {
                                $language = $data->language ? json_decode($data->language, true) : null;
                                $language = is_array($language) ? implode(', ', $language) : 'null';
                                $allappointments=$data->tutorAppointments->count();
                               
                                return '<div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm bg-light rounded p-1"><img src="'.Helpers::image($data->photo, 'user/avatar/').'" alt=""
                                                class="img-fluid d-block"></div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fs-14 mb-1">'.$data->name.'</h5>
                                        <p class="text-muted mb-0"> Language: <span class="fw-medium">' .$language.'</span></p>
                                        <p class="fw-medium mb-0"> Apointments: <span class="fw-medium">' .$allappointments.'</span></p>
                                    </div>
                                </div>';
                            })


                            ->addColumn('status', function(User $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.tutor.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.tutor.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })

                            ->addColumn('action', function(User $data) {
                                return '<div class="action-list">
                                
                                <a href="'.route('admin.tutor.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a>

                                <a href="'.route('admin.tutor.appointment.index',$data->id).'" class="btn btn-primary btn-sm fs-13 waves-effect waves-light"><i class="ri-eye-fill align-middle fs-16 me-2"></i>View Appointments</a> 
                               ';
                                // <a href="'.route('admin.tutor.schedule.create',$data->id).'" class="btn btn-success btn-sm fs-13 waves-effect waves-light"><i class="ri-add-line  align-middle fs-16 me-2"></i>Create Schedule</a>
                                // </div>
                              
                                // <a href="'.route('admin.tutot.create',$data->slug).'" class="btn btn-success btn-sm fs-13 waves-effect waves-light"><i class="ri-add-line  align-middle fs-16 me-2"></i>Create QuizBank</a>
                                // <a href="'.route('admin.tutot.index',$data->slug).'" class="btn btn-primary btn-sm fs-13 waves-effect waves-light"><i class="ri-eye-fill align-middle fs-16 me-2"></i>View QuizBank</a> 

                                // <a data-href="' . route('admin.tutor.delete',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete</a>
                            }) 
                            ->rawColumns(['name','select','banner','status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side

       
    }

    public function index()
    {   
        // $datas = User::orderBy('id', 'desc')->get();
        // foreach ($datas as $data) {
        //     // if (!is_null($data->language)) {
        //         $data->language = null;
        //         $data->save();
        //     // }
        // }
        // dd($datas);

        $subplans=SubPlan::active()->get();
        return view('admin.tutors.index',compact('subplans'));
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
        return view('admin.tutors.create',compact('companies','subplans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //--- Validation Section
        $request->validate([
            'name' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'price' => 'required|numeric|min:0',

        ],[
            'name.unique' => 'The username has already been taken.'
        ]);
        $gs=GeneralSetting::find(1);

        $data = new User();
        $input=$request->all();
        // Upload intro video
        if ($request->hasFile('intro_video')) {
            $data->intro_video = Helpers::upload('user/intro_videos/', config('fileformats.video'), $request->file('intro_video'));
        }
        // Upload intro photo
        if ($request->hasFile('photo')) {
            $data->photo = Helpers::upload('user/avatar/', config('fileformats.image'), $request->file('photo'));
        }
        $subplanIds = $request->input('subplan_ids');
        $data->subplan_ids =$subplanIds? json_encode($subplanIds):null;
        $data->slug=Helpers::slug($request->name);

        if ($gs->is_affilate == 1) {
          $affiliate_code=substr(uniqid(), 0, 8);
          while(User::where('affiliate_code', '=', $affiliate_code)->exists() || Coupon::where('coupon_code', '=', $affiliate_code)->exists())
          {
           $affiliate_code=substr(uniqid(), 0, 8);
          }
          $input['affiliate_code'] = $affiliate_code;
        }
        //users default data
        $temporaryPassword =$data->slug.Str::random(4);
        $input['password'] = bcrypt($temporaryPassword);  
        $data->is_email_verified=0;
        $data->role_id=2;
        $data->role_type='user';
        $data->language=$request->language?json_encode($request->language):null;


        $data->fill($input)->save();

        // Send Email to tutor
        $to = $data->email;
        $loginLink = route('user.login');
        $subject = "Welcome to $gs->name - Your Login Credentials";
        $msg = "
            <p>Welcome to Our Platform!</p>
            <p>Your tutor account has been created successfully.</p>
            <p>Your login credentials:</p>
            <ul>
                <li><strong>Email:</strong> $to</li>
                <li><strong>Password:</strong> $temporaryPassword</li>
            </ul>
            <p>Please change your password after logging in for security reasons.</p>
            <p>You can log in to your account <a href='$loginLink'>here</a>.</p> <!-- Add the login link -->
            <p>Thank you for joining us!</p>
        ";

       $maildata = [
                'to' => $to,
                'subject' => $subject,
                'body' => $msg,
            ];
        $mailer = new GeniusMailer();
        $mailer->sendCustomMail($maildata); 
        // Send Email to tutor ends

        toastr()->success('Data has been saved successfully!');
        return redirect()->route('admin.tutor.edit',$data->id);
        // return redirect()->route('admin.tutor.schedule.create',$data->id);
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
        $data=User::find($id);
        return view('admin.tutors.edit',compact('data','companies','subplans'));
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
        //--- Validation Section
        $request->validate([
            'name'=>'required|unique:users,name,'.$id,
            'email'   => 'required|email|unique:users,email,'.$id,
            'price' => 'required|numeric|min:0',

        ],[
            'name.unique' => 'The username has already been taken.'
        ]);

        $data=User::findOrfail($id);
        $input=$request->all();


        if ($request->hasFile('intro_video')) {
            $data->intro_video = Helpers::update('user/intro_videos/', $data->intro_video, config('fileformats.video'), $request->file('intro_video'));
        }
        // Upload intro photo
        if ($request->hasFile('photo')) {
            $data->photo = Helpers::update('user/avatar/', $data->photo=='user.png'?'':$data->photo, config('fileformats.image'), $request->file('photo'));
        }
        $subplanIds = $request->input('subplan_ids');
        $data->subplan_ids =$subplanIds? json_encode($subplanIds):null;
        $data->slug=Helpers::slug($request->name);
        $data->language=$request->language?json_encode($request->language):null;

        $data->fill($input)->update();

        toastr('Data has been updated successfully!', 'success');
        return redirect()->route('admin.tutor.edit',$data->id);
        
    }

        //*** GET Request
    public function status($id1,$id2)
    {
        $data = User::findOrFail($id1);
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
        $data = User::findOrFail($id);
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
                    DB::table('users')->whereIn('id', $ids)->update(['status' => $request->input('mb_status')]);
                    break;

                case 'mb_change_price':
                    User::whereIn('id', $ids)->update(['price' => $request->mb_price]);
                    break;

                case 'mb_membership_level':
                    $subplanIds = $request->input('subplan_ids');
                    $subplanIdss=$subplanIds? json_encode($subplanIds):null;
                    User::whereIn('id', $ids)->update(['subplan_ids' => $subplanIdss]);
                    break;

                case 'mb_language':
                    User::whereIn('id', $ids)->update(['language' => $request->language]);
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
}