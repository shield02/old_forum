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
     * Earger load this relationships.
     * 
     * @var array $with
     */
    protected $with = ['owner', 'favorites'];

    /**
     * Append any custom attributes.
     * 
     * @var array $appends
     */
    protected $appends = ['favoritesCount', 'isFavorited'];

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
     * 
     * 
     * @return string[]
     */
    public function mentionedUsers()
    {
        preg_match_all('/\@([^\s\.]+)/', $this->body, $matches);

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
}
