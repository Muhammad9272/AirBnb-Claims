<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\ScheduleLogic;
use App\CentralLogics\UserAccess;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
class LearningController extends Controller
{   
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }
    public function myLearning( )
    { 
      $banner=Banner::active()->where('for_section','section_interview_learning')->first();
      return view('user.learning.my-learning',compact('banner'));
    }

    public function index()
    { 
       $query = User::where('role_id', 2)->active();
       
       // Order tutors by appointment count in descending order for the top tutors
       $topTutors = $query->withCount('tutorAppointments')
           ->orderByDesc('tutor_appointments_count')
           ->take(5)
           ->get();

       // Reset the query builder for fetching the remaining tutors
       $query = User::where('role_id', 2)->active();

       // Search functionality
       $search = $this->request->input('search');
       if ($search) {
           $query->where(function($q) use($search) {
                $q->where('name', 'LIKE', '%'.$search.'%')
                  ->orWhere('tags', 'LIKE', '%'.$search.'%')
                   ->orWhere('language', 'LIKE', '%'.$search.'%')
                    ->orWhere('coaching_services', 'LIKE', '%'.$search.'%');
            });
       }

       // Exclude the top tutors from the query for remaining tutors
       $query->whereNotIn('id', $topTutors->pluck('id')->all());

       // Fetch remaining tutors ordered by name
       $tutorsData = $query->orderBy('name', 'asc')->paginate(12);

       // Append the search query to the pagination links
       $tutorsData->appends(['search' => $search]);

       $banner = Banner::active()->where('for_section', 'section_interview_coaching')->first();
       
       return view('user.learning.interview-coaching', compact('topTutors', 'tutorsData', 'search', 'banner'));
    }
    public function indexold()
    { 
       $query=User::where('role_id', 2)->active();
        // search functionality
        $search = $this->request->input('search');
        if ($search) {
           $query->where(function($q) use($search) {
                $q->where('name', 'LIKE', '%'.$search.'%')
                  ->orWhere('tags', 'LIKE', '%'.$search.'%')
                   ->orWhere('language', 'LIKE', '%'.$search.'%')
                    ->orWhere('coaching_services', 'LIKE', '%'.$search.'%');
            });
        }

        $tutorsData = $query->orderBy('name', 'asc')->paginate(12);
        // Append the search query to the pagination links
        $tutorsData->appends(['search' => $search]);
        $banner=Banner::active()->where('for_section','section_interview_coaching')->first();
        return view('user.learning.interview-coaching', compact('tutorsData', 'search','banner'));
    }

    public function show($tutor_slug)
    {   
        $data = User::where('slug', $tutor_slug)->where('role_id', 2)->active()->orderBy('id', 'desc')->firstOrFail();
        $date = Carbon::now()->format('d M, Y');
        $schedule = ScheduleLogic::getScheduleForDate($data->id, $date);
        $response=UserAccess::hasAccess(auth()->user(),'interview',$data->id);
        $UserAccess=$response['access']?true:false;
        return view('user.learning.tutorial-booking', compact('data','schedule','UserAccess'));
    }

    // public function getScheduleForDate(Request $request, $date)
    // {
        
    //     $date = Carbon::createFromFormat('d M, Y', $date)->format('Y-m-d');

    //     $day_name = strtolower(date('l', strtotime($date)));
    //     $tutorId = $request->input('tutor_id');

    //     $schedule = Schedule::where('user_id', $tutorId)
    //         ->where('start_date', '<=', $date)
    //         ->where('end_date', '>=', $date)
    //         ->where('day_of_week', $day_name)
    //         ->firstOrFail();

    //     return view('user.learning.partials.slots', compact('schedule'));
    // }


    public function showSchedule(Request $request)
    {
        $tutorId = $request->input('tutor_id');
        $date = $request->input('date');
        $schedule = ScheduleLogic::getScheduleForDate($tutorId, $date);

        if (!$schedule) {
            // Tutor is not available on the selected date
            return view('user.learning.partials.slots');
        }

        // Tutor is available on the selected date, display schedule
        return view('user.learning.partials.slots', compact('schedule'));
    }



   


}
