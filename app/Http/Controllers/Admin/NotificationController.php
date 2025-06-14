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
            
            // Redirect to the linked resource or back
            if ($notification->link) {
                return redirect($notification->link);
            }
        }
        
        return back()->with('success', 'Notification marked as read');
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
}
