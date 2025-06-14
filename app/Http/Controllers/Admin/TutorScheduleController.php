<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvailableDays;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
class TutorScheduleController extends Controller
{
    public function create($tutor_id)
    { 
      $userschedule=Schedule::where('user_id',$tutor_id)->first();
      return view('admin.tutors.schedule',compact('tutor_id','userschedule'));
    }
    public function store(Request $request,$tutor_id)
    {

        // Validate form data
        $validatedData = $request->validate([
            'start_date' => 'required',
            'end_date' => 'nullable',
            'repeat_interval' => 'required',
            'available_days' => 'required|array',
            // 'available_days.*' => 'in:sunday,monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
     // dd($request->end_date);
        $start_date = Carbon::createFromFormat('d M, Y', $request->start_date)->format('Y-m-d');
        $end_date =$request->end_date? Carbon::createFromFormat('d M, Y', $request->end_date)->format('Y-m-d'):null;
      foreach($request->available_days as $day){

          $day_name = strtolower(date('l', strtotime($start_date)));
          if($day!= $day_name){
            return redirect()->back()->with('error','Start Date(Day) must be matched with selected day');
          }
          $existingSchedule = Schedule::where('user_id', $tutor_id)
                            ->where('day_of_week', $day)
                            ->first();

          // Create or update schedule
          $data = $existingSchedule ?: new Schedule();
          $data->user_id=$tutor_id;     
          $data->start_date=$start_date; 
          $data->end_date = $end_date;
          $data->repeat_interval = $request->input('repeat_interval');
          $data->day_of_week = $day;
          $data->start_time = $request->input('start_time');
          $data->end_time = $request->input('end_time');
          $data->meeting_id = $request->input('meeting_id');
          $data->save();
        }


        toastr()->success('Data has been added successfully!');
        return redirect()->route('admin.tutor.edit',$tutor_id);
        // return redirect()->route('admin.tutor.schedule.create',$tutor_id);
    }

    public function edit(Request $request,$id=null){
      $daySelected=$request->selectedday;
      $data=Schedule::find($id);
      $edit_form=1;
      return view('admin.tutors.partials.schedule',compact('data','edit_form','daySelected'));
    }
    // public function update(Request $request,$tutor_id){
    //      $validatedData = $request->validate([
    //         'available_days' => 'required',
    //     ]);
    //     // $data=Schedule::find($request->schedule_id);
    //     $existingSchedule = Schedule::where('tutor_id', $tutor_id)
    //                         // ->where('start_date', $validatedData['start_date'])
    //                         // ->where('end_date', $validatedData['end_date'])
    //                         ->where('repeat_interval', $request->input('repeat_interval'))
    //                         ->first();
    //     // Create or update schedule
    //     $data = $existingSchedule ?: new Schedule();

    //     $data->start_date=Carbon::parse($request->start_date)->format('Y-m-d'); 
    //     $data->end_date = Carbon::parse($request->end_date)->format('Y-m-d');
    //     $data->repeat_interval = $request->input('repeat_interval');
     
    //     $data->start_time = $request->input('start_time');
    //     $data->end_time = $request->input('end_time');
    //     $data->meeting_id = $request->input('meeting_id');
    //     $data->save();

    //     toastr()->success('Data has been updated successfully!');
    //     return redirect()->back();
        
    // }

    public function destroy($id){
      $data=Schedule::find($id);
      $data->delete();
      toastr()->success('Data has been deleted successfully!');
      return redirect()->back();

    }
}
