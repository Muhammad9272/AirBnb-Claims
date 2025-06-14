<?php

namespace App\CentralLogics;
use App\Models\CareerEvent;
use App\Models\QuizBankManagement;
use App\Models\Schedule;
use App\Models\SubPlan;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Session; 
class ScheduleLogic
{


    // Helper function to get tutor schedule for a specific date
    public static function getScheduleForDate($tutorId, $date)
    {   
        $date = Carbon::createFromFormat('d M, Y', $date)->format('Y-m-d');
        $day_name = strtolower(date('l', strtotime($date)));
       
        $schedule = Schedule::where('user_id', $tutorId)
            ->where('day_of_week', $day_name)
            ->whereDate('start_date', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->whereDate('end_date', '>=', $date)
                      ->orWhereNull('end_date');
            })
            ->first();        
        // Check if the selected date falls within the repeating interval
        if($schedule){
            $interval = $schedule->repeat_interval;
            if ($interval != 'weekly') {
                $start = Carbon::createFromFormat('Y-m-d', $schedule->start_date);
                $diff = Carbon::createFromFormat('Y-m-d', $date)->diffInDays($start);
                if ($diff % ($interval == 'biweekly' ? 14 : ($interval == 'triweekly' ? 21 : 28)) != 0) {
                     // Tutor is not available on the selected date
                    return null;
                }
            }
        }
        
        return $schedule;
    }

}
