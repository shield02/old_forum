<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Reply;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    /**
     * RepliesController Constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Persist a new reply.
     *
     * @param integer $channelId
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread)
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);

        $thread->addReply([
            'body'      => request('body'),
            'user_id'   => auth()->id()
        ]);

        return back()
            ->with('flash', 'Your reply has been left.');
    }

    /**
     * 
     * 
     * @param App\Reply $reply
     * @return void
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        
        $reply->update(request(['body']));
    }

    /**
     * Delete the given reply.
     * 
     * @param App\Reply $reply
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }

}
