<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Favoritable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    /**
     * Don't auto-apply mass assignment protection.
     * 
     * @var array $guarded
     */
    protected $guarded = [];

    /**
     * The relations to eager load on every query.
     * 
     * @var array $with
     */
    protected $with = ['owner', 'favorites'];

    /**
     * The accessors to append to model's array form.
     * 
     * @var array $appends
     */
    protected $appends = ['favoritesCount', 'isFavorited', 'isBest'];

    /**
     * Boot the reply instance.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
        });

        static::deleted(function ($reply) {
            $reply->thread->decrement('replies_count');
        });
    }
    
    /**
     * A reply has an owner.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A reply belongs to a thread.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Determine if the reply was just published a moment ago.
     * 
     * @return bool
     */
    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    /**
     * Fetch all mentioned users within the reply's body.
     * 
     * @return string[]
     */
    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);

        return $matches[1];

    }

    /**
     * Get a string path for the thread.
     * 
     * @return string
     */
    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    /**
     * Set the body attribute.
     * 
     * @param mixed $body
     */
    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="/profiles/$1">$0</a>', $body);
    }

    /**
     * Determine if the current reply is marked as the best.
     * 
     * @return bool
     */
    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    /**
     * 
     * 
     * @return bool
     */
    public function getIsBestAttribute()
    {
        return $this->isBest();
    }
}
