<?php

namespace App;

use App\Filters\ThreadsFilters;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;

class Thread extends Model
{
    use RecordsActivity;

    /**
     * Don't auto-apply mass assignment protection.
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * The relationships to always eager-load.
     * 
     * @var array
     */
    protected $with = ['creator', 'channel'];

    /**
     * Append any custom attributes.
     * 
     * @var array $appends
     */
    protected $appends = ['isSubscribedTo'];

    /**
     * Boot the model.
     * A global scope is a query scope that is automatically applied to all the queries.
     * 
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }

    /**
     * Get a string path for the thread.
     * 
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    /**
     * A thread belongs to a creator.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A thread is assigned to a channel.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * A thread may have many replies.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Add a reply to the thread.
     * 
     * @param array $reply
     * @return Reply
     */
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        // Prepare notifications for all subscribers.
        $this->subscriptions
            ->filter(function ($sub) use ($reply) {
                return $sub->user_id != $reply->user_id;
            })
            ->each->notify($reply); // Higher order collections approach
            
            // ->each(function ($sub) use ($reply) {
            //     $sub->notify( $reply);
            // });

            return $reply;
    }

    /**
     * Apply all relevant thread filters.
     * 
     * @param Builder $query
     * @param App\Filters\ThreadFilters $filters
     * @return 
     */
    public function scopeFilter($query, ThreadsFilters $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Subscribe a user to the current thread.
     * 
     * @param int|null $userId
     * @return $this
     */
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    /**
     * Unsubscribe a user from the current thread.
     * 
     * @param int|null $userId
     */
    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    /**
     * A thread has many subscriptions.
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    /**
     * 
     * 
     * @return boolean
     */
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }
}
