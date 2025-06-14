<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\OrderLogic;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TutorAppointmentController extends Controller
{



    public function index($tutor_id)
    { 
        $appointments = Appointment::where('tutor_id', $tutor_id)->latest()->get();
        $events = [];
        
        foreach ($appointments as $appointment) {
            $start_time = Carbon::createFromFormat('Y-m-d H:i:s', $appointment->start_date . ' ' . $appointment->start_time)->format('Y-m-d\TH:i:s');
            $end_time = Carbon::createFromFormat('Y-m-d H:i:s', $appointment->end_date . ' ' . $appointment->end_time)->format('Y-m-d\TH:i:s');

            $event = [
                'id' => $appointment->id,
                'title' => $appointment->student ? $appointment->student->name : $appointment->title, // display student's name as event title if available
                'start' => $start_time, // combine start date and time
                'end' => $end_time, // combine end date and time
                'backgroundColor' => $appointment->status=='pending'?'#3788d8':'#0ab39c',
                'borderColor' =>$appointment->status=='pending'?'#3788d8':'#0ab39c',
            ];
            
            array_push($events, $event);
        }

        return view('admin.tutors.appointments', compact('appointments', 'events', 'tutor_id'));
    }


    public function store(Request $request, $tutor_id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required',
            'end_date' => 'nullable',
            'start_time' => 'required',
            'end_time' => 'required',
            'id' => 'nullable|integer|exists:appointments,id'
        ]);

        $data = [
            'id'=>$request->id,
            'student_id'=>null,
            'tutor_id'=>$tutor_id,
            'meeting_id'=>$request->meeting_id,
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' =>$request->start_time,
            'end_time' => $request->end_time,
            'details'=>$request->details,
            'status' => $request->status
        ];

        $response=OrderLogic::storeOrUpdateAppointment($data, $tutor_id);
        return redirect()->back()->with('success', $response['message']); 
    }

    public function edit($tutor_id,$appointment_id)
    {
        $appointmentdata = Appointment::where('tutor_id', $tutor_id)->findOrFail($appointment_id);
        return view('admin.tutors.partials.add-appointment-form', compact('appointmentdata', 'tutor_id'));
    }



    public function destroy(Request $request,$tutor_id)
    {

        $appointment = Appointment::where('tutor_id', $tutor_id)->findOrFail($request->appointment_id);
        $appointment->delete();
    }

}
