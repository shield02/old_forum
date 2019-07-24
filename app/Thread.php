<?php

namespace App;

use App\Filters\ThreadsFilters;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    /**
     * Don't auto-apply mass assignment protection.
     * 
     * @var array
     */
    protected $guarded = [];

    protected $with = ['creator', 'channel'];

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
        return $this->replies()->create($reply);
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
}
