<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserNotificationsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 
     * 
     * @return mixed
     */
    public function index()
    {
        return auth()->user()->unreadnotifications;
    }

    /**
     * 
     * 
     * @param App\User $user
     * @param int $notificationId
     */
    public function destroy(User $user, $notificationId)
    {
        auth()->user()->notifications()->findOrFail($notificationId)->markAsRead();
    }
}
