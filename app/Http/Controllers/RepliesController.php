<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Reply;
use App\Inspections\Spam;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    /**
     * RepliesController Constructor
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * 
     * 
     * 
     * @param mixed $channelId
     * @param App\Thread $thread
     * @return mixed
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * Persist a new reply.
     *
     * @param integer $channelId
     * @param  Thread  $thread
     * @param  Spam  $spam
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread, Spam $spam)
    {
        $this->validate(request(), [ 'body' => 'required' ]);
        $spam->detect(request('body'));

        $reply = $thread->addReply([
            'body'      => request('body'),
            'user_id'   => auth()->id()
        ]);

        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

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
