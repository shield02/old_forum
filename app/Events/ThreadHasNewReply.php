<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class ThreadHasNewReply
{
    use SerializesModels;

    public $thread;

    public $reply;

    /**
     * Create a new event instance.
     *
     * @param mixed \App\Thread $thread
     * @param mixed \App\Reply $reply
     */
    public function __construct($thread, $reply)
    {
        $this->thread = $thread;

        $this->reply = $reply;
    }

}
