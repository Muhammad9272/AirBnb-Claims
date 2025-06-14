<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(15);
        return view('user.notifications.index', compact('notifications'));
    }
    
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->is_read = true;
        $notification->save();
        
        // Redirect to the linked resource or back
        if ($notification->link) {
            return redirect($notification->link);
        }
        
        return back()->with('success', 'Notification marked as read');
    }
    
    public function markAllAsRead()
    {
        Auth::user()->notifications()->update(['is_read' => true]);
        
        return back()->with('success', 'All notifications marked as read');
    }
    
    public function delete($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return back()->with('success', 'Notification deleted');
    }
    
    public function deleteAll()
    {
        Auth::user()->notifications()->delete();
        
        return back()->with('success', 'All notifications deleted');
    }
}
