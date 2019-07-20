<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorite extends Model
{
    use RecordsActivity;

    /**
     * Don't auto apply mass assignment protection.
     * 
     * @var array $guarded
     */
    protected $guarded = [];

    /**
     * 
     * 
     * @return Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function favorited()
    {
        return $this->morphTo();
    }
}
