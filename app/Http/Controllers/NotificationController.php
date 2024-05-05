<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function markRead(Request $request){
        // return Notification::where('notifiable_id',$request->get('id'))->update(['read_at'=>now()]);
        return auth()->user()->unreadNotifications->markAsRead();
    }
}
