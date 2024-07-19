<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function show(){
        return view('notif_display');
    }

    public function index()
    {
        $notifications = Notification::where('user_id_to', Auth::user()->id)->with('froms')->where('status', 'UNREAD')->get()->sortByDesc('id');

        return view('notification_list', compact('notifications'));
    }

    public function readThenRedirect($id){
        $notif = Notification::findOrFail($id);
        $notif->status = 'READ';
        $notif->save();

        return redirect($notif->url);
    }

    public function clearAllNotification(){
        
        Notification::where('user_id_to', Auth::user()->id)->where('status', 'UNREAD')->update(['status'=>'READ']);

        return redirect('notification')->with('success', 'All notifications has been cleared');
    }   
}
