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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread)
    {
        try {
            $this->validateReply();
    
            $reply = $thread->addReply([
                'body'      => request('body'),
                'user_id'   => auth()->id()
            ]);
        } catch (\Exception $e) {
            return response(
                'Sorry, your reply could not be saved at this time.', 422
            );
        }
        
        return $reply->load('owner');
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
            $this->validateReply();
            
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

    protected function validateReply()
    {
        $this->validate(request(), ['body' => 'required']);
        resolve(Spam::class)->detect(request('body'));
    }
}
