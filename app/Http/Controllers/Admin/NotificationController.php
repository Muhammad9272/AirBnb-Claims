<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function index()
    {
        // Get the admin user from the User model (assuming your admin auth maps to User model)
        $adminUser = User::where('email', Auth::guard('admin')->user()->email)->first();
        
        if (!$adminUser) {
            $notifications = collect();
        } else {
            $notifications = $adminUser->notifications()->latest()->paginate(15);
        }
        
        return view('admin.notifications.index', compact('notifications'));
    }
    
    public function markAsRead($id)
    {
        // Get the admin user from the User model
        $adminUser = User::where('email', Auth::guard('admin')->user()->email)->first();
        
        if ($adminUser) {
            $notification = $adminUser->notifications()->findOrFail($id);
            $notification->is_read = true;
            $notification->save();
        }
        
        return response()->json(['status' => 'success']);
    }
    
    public function markAllAsRead()
    {
        // Get the admin user from the User model
        $adminUser = User::where('email', Auth::guard('admin')->user()->email)->first();
        
        if ($adminUser) {
            $adminUser->notifications()->update(['is_read' => true]);
        }
        
        return back()->with('success', 'All notifications marked as read');
    }
    
    public function delete($id)
    {
        // Get the admin user from the User model
        $adminUser = User::where('email', Auth::guard('admin')->user()->email)->first();
        
        if ($adminUser) {
            $notification = $adminUser->notifications()->findOrFail($id);
            $notification->delete();
        }
        
        return back()->with('success', 'Notification deleted');
    }
    
    public function deleteAll()
    {
        // Get the admin user from the User model
        $adminUser = User::where('email', Auth::guard('admin')->user()->email)->first();
        
        if ($adminUser) {
            $adminUser->notifications()->delete();
        }
        
        return back()->with('success', 'All notifications deleted');
    }
    
    public function getUnreadCount()
    {
        // Get the admin user from the User model
        $adminUser = User::where('email', Auth::guard('admin')->user()->email)->first();
        
        $count = 0;
        if ($adminUser) {
            $count = $adminUser->unreadNotifications()->count();
        }
        
        return response()->json(['count' => $count]);
    }

    public function getNotifications()
    {
        $notifications = Notification::where('user_id', Auth::guard('admin')->id())
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(10) // Get latest 10 unread notifications
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'link' => $notification->link,
                    'created_at' => \Carbon\Carbon::parse($notification->created_at)->diffForHumans(),
                ];
            });
        
        return response()->json(['notifications' => $notifications]);
    }
}
