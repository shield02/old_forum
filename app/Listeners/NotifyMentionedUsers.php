<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWhereMentioned;
use App\User;

class NotifyMentionedUsers
{

    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        User::whereIn('name', $event->reply->mentionedUsers())
        ->get()
        ->each(function ($user) use ($event) {
            $user->notify(new YouWhereMentioned($event->reply));
        });
    }
}
