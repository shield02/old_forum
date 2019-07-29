<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class ThreadSubscriptionsController extends Controller
{
    /**
     * 
     * 
     * @param mixed $channelId
     * @param App\Thread $thread
     */
    public function store($channelId, Thread $thread)
    {
        $thread->subscribe();
    }

    /**
     * 
     * 
     * @param mixed $channelId
     * @param App\Thread $thread
     */
    public function destroy($channelId, Thread $thread)
    {
        $thread->unsubscribe();
    }
}
