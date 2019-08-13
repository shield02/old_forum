<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Reply;
use App\Rules\SpamFree;
use App\Http\Requests\CreatePostRequest;

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
     * @param Thread  $thread
     * @param CreatePostRequest $form
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
        return $thread->addReply([
            'body'      => request('body'),
            'user_id'   => auth()->id()
        ])->load('owner');
    }

    /**
     * 
     * 
     * @param Reply $reply
     * @return void
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            request()->validate(['body' => ['required', new SpamFree]]);
            
            $reply->update(request(['body']));
        } catch (\Exception $e) {
            return response(
                'Sorry, your reply could not be saved at this time.', 422
            );
        }

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
