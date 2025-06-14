<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
class NotificationsController extends Controller
{  
    public function __construct(){
        $this->middleware('auth');
    }
    public function indexAjax()
    {   
        $notifications = auth()->user()->unreadNotifications()->latest()->take(10)->get();
        $count = $notifications->count();
        $view = view('front.includes.notifications', [
            'notifications' => $notifications,
            'count' => $count
        ])->render();
        
        return response()->json(['view' => $view, 'count' => $count]);
    }

    public function index($filter='')
    {   
        $notifications = auth()->user()->notifications;
        // if ($request->ajax()) {
        switch($filter) {
            case 'read':
                $notifications = $notifications->whereNotNull('read_at');
                break;
            case 'unread':
                $notifications = $notifications->whereNull('read_at');
                break;
        }
        
        return view('user.notifications.index', [
            'notifications' => $notifications->paginate(10)
        ]);

    }

    public function markAsRead(Request $request)
    {  
        $user = Auth::user();
        $notificationIds = $request->input('notification_ids', []);
        // mark notifications as read
        $notifications = $user->unreadNotifications()->whereIn('id', $notificationIds)->get();
        $notifications->markAsRead();

        return redirect()->back()->with('success','Notification marked as read');
    }

    public function delete(Request $request)
    {
        $user = Auth::user();
        $notificationIds = $request->input('notification_ids', []);

        // delete notifications
        $notifications = $user->notifications()->whereIn('id', $notificationIds)->get();
        $notifications->each(function ($notification) {
            $notification->delete();
        });

        return redirect()->back()->with('success','Notification Deleted Successfully');;
    }

    
}
