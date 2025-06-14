<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\UserAccess;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\CareerEvent;
use App\Models\CareerEventRegistration;
use Auth;
use Illuminate\Http\Request;
class EventController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }


    public function index( )
    {
       $query=CareerEvent::active();
       $search = $this->request->input('search');
        if ($search) {
           $query->where(function($q) use($search) {
                $q->where('name', 'LIKE', '%'.$search.'%')
                  ->orWhere('event_type', 'LIKE', '%'.$search.'%')
                   ->orWhere('host_name', 'LIKE', '%'.$search.'%');
            });
        }

      $careerEvents=$query->orderBy('name', 'asc')->paginate(12); 
      $banner=Banner::active()->where('for_section','section_career_events')->first(); 
      return view('user.events.index',compact('careerEvents','banner','search'));
    }
    public function show($event_slug)
    {
      $data=CareerEvent::where('slug',$event_slug)->active()->firstOrFail(); 
      $userEvent=CareerEventRegistration::where('user_id',Auth::id())->where('event_id',$data->id)->first(); 
      $response=UserAccess::hasAccess(auth()->user(),'events',$data->id);
      $UserAccess=$response['access']?true:false;

      return view('user.events.show',compact('data','userEvent','UserAccess'));
    }
}
