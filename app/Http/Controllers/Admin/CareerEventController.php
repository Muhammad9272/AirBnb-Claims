<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\CareerEvent;
use App\Models\CareerEventRegistration;
use App\Models\SubPlan;
use Carbon\Carbon;
use DB;
use DataTables;
use Illuminate\Http\Request;
use Validator;
class CareerEventController extends Controller
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
        $datas=CareerEvent::orderBy('id','desc')->get();  
        return DataTables::of($datas)
                            // ->addIndexColumn()                            
                            ->addColumn('select', function(CareerEvent $data) {
                                return 
                                '<div class=""><input type="checkbox" class="checkbox form-check-input sub_select" name="" data-id="'.$data->id.'"></div>';

                            })
                            ->addColumn('registered_users', function(CareerEvent $data) {
                                $totalusers=CareerEventRegistration::where('event_id',$data->id)->count(); 
                                return $totalusers;
                            })
                            ->editColumn('event_date_time', function(CareerEvent $data) {
                                return $data->event_date_time?Carbon::parse($data->start_date)->format('F d, Y  H:i A'):'';
                            })

                            ->addColumn('permission', function(CareerEvent $data) {
                                return $data->price?$data->price:'';
                            })
                            // ->addColumn('company_id', function(CareerEvent $data) {
                            //     return $data->company?$data->company->name:'';
                            // })

                            ->addColumn('status', function(CareerEvent $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.career.event.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.career.event.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })

                            ->addColumn('action', function(CareerEvent $data) {
                                return '<div class="action-list ">
                                
                                <a href="'.route('admin.career.event.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a>
                                <a href="'.route('admin.career.event.registered_users',$data->id).'" class="btn btn-info mt-2 btn-sm fs-13 waves-effect waves-light"><i class="ri-registered-fill align-middle fs-16 me-2"></i>Reg Participants</a>
                                </div>';

                                // <a data-href="' . route('admin.career.event.delete',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete</a>
                            }) 
                            ->rawColumns(['permission','select','event_date_time','status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side

       
    }

    public function index()
    {   
        $subplans=SubPlan::active()->get();
        return view('admin.careerevents.index',compact('subplans'));
    }
    
    public function viewEventCalender($value='')
    {

        $events = CareerEvent::all();
        // Format events data in the required format for FullCalendar
        $formattedEvents = [];
        foreach ($events as $event) {
            $formattedEvents[] = [
                'title' => $event->name,
                'start' => $event->event_date_time,
                // Add other event properties as needed
            ];
        }
        return view('admin.careerevents.calender', compact('formattedEvents'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $subplans=SubPlan::active()->get();
        return view('admin.careerevents.create',compact('subplans'));
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
            'name' => 'required|string|max:150|unique:career_events,name',
            'details' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            // 'subplan_ids' => 'required|array',
            // 'subplan_ids.*' => 'required|exists:sub_plans,id',
            'intro_video' =>'nullable|file|max:50000|mimes:'.config('fileformats.videoBV'),
            'event_date_time'=>'required'
        ];
         $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = new CareerEvent();
        $input=$request->all();
        // Upload intro video
        if ($request->hasFile('intro_video')) {
            $data->intro_video = Helpers::upload('events/intro_videos/', config('fileformats.video'), $request->file('intro_video'));
        }
         // Upload intro video
        if ($request->hasFile('photo')) {
            $data->photo = Helpers::upload('events/', config('fileformats.image'), $request->file('photo'));
        }
        $date_time = Carbon::createFromFormat('d M, Y H:i', $request->input('event_date_time'));
        $data->event_date_time = $date_time->format('Y-m-d H:i:s');
        $subplanIds = $request->input('subplan_ids');
        $data->subplan_ids =$subplanIds? json_encode($subplanIds):null;
        $data->slug=Helpers::slug($request->name);

        $data->fill($input)->save();

         return response()->json([
            'success' => true,
            'msg' => "Data has been saved successfully!",
            'route'=>route('admin.career.event.edit',$data->id),
        ]);
        

        

        // toastr()->success('Data has been saved successfully!');
        // return redirect()->route('admin.career.event.edit',$data->id);
        // return redirect()->route('admin.career.event.edit',$data->id);
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
        $subplans=SubPlan::active()->get();
        $data=CareerEvent::find($id);
        return view('admin.careerevents.edit',compact('data','subplans'));
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
            'name' => 'required|string|max:150|unique:career_events,name,'.$id,
            'details' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            // 'subplan_ids' => 'required|array',
            // 'subplan_ids.*' => 'required|exists:sub_plans,id',
            'intro_video' =>'nullable|file|max:50000|mimes:'.config('fileformats.videoBV'),
            'event_date_time'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }


        $data=CareerEvent::findOrfail($id);
        $input=$request->all();
        // Upload intro video
        if ($request->hasFile('intro_video')) {
            $data->intro_video = Helpers::update('events/intro_videos/',$data->intro_video, config('fileformats.video'), $request->file('intro_video'));
        }
         // Upload intro video
        if ($request->hasFile('photo')) {
            $data->photo = Helpers::update('events/',$data->photo, config('fileformats.image'), $request->file('photo'));
        }
        $date_time = Carbon::createFromFormat('d M, Y H:i', $request->input('event_date_time'));
        $data->event_date_time = $date_time->format('Y-m-d H:i:s');

        $subplanIds = $request->input('subplan_ids');
        $data->subplan_ids =$subplanIds? json_encode($subplanIds):null;
        $data->slug=Helpers::slug($request->name);
        $data->fill($input)->update();

        return response()->json([
            'success' => true,
            'msg' => "Data has been updated successfully",
            'route'=>route('admin.career.event.edit',$data->id),
        ]);

        // toastr('Data has been updated successfully!', 'success');
        // return redirect()->route('admin.career.event.edit',$data->id);
        
    }

        //*** GET Request
    public function status($id1,$id2)
    {
        $data = CareerEvent::findOrFail($id1);
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
        $data = CareerEvent::findOrFail($id);
        $data->delete();
    }



    public function bulkaction(Request $request)
    {
        $selectedIds = $request->input('selectedrowsIds');
        $action = $request->input('bulkactions');

        if(empty($selectedIds)) {
            return response()->json([
                'success' => false,
                'message' => $request->all(),
            ]);
        }
        $ids = explode(',', $selectedIds);

        DB::beginTransaction();
        try {
            switch ($action) {
                case 'mb_change_status':
                    DB::table('career_events')->whereIn('id', $ids)->update(['status' => $request->input('mb_status')]);
                    break;

                case 'mb_change_price':
                    CareerEvent::whereIn('id', $ids)->update(['price' => $request->mb_price]);
                    break;

                case 'mb_membership_level':
                    $subplanIds = $request->input('subplan_ids');
                    $subplanIdss=$subplanIds? json_encode($subplanIds):null;
                    CareerEvent::whereIn('id', $ids)->update(['subplan_ids' => $subplanIdss]);
                    break;

                case 'mb_meeting_room':
                    CareerEvent::whereIn('id', $ids)->update(['meeting_id' => $request->meeting_id]);
                    break;

                case 'mb_event_date_time':
                    DB::table('career_events')->whereIn('id', $ids)->update(['event_date_time' => $request->input('event_date_time')]);
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