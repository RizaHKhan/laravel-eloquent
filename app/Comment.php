<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $dispatchesEvents = [];

    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('rating', function (Builder $builder) {
            $builder->where('rating', '>', 2);
        });
    }

    public function scopeRating($query, int $value = 4)
    {
        return $query->where('rating', '>', $value);
    }

    public function getRatingAttribute($value) // modify columns when accessing model
    {
        return $value + 10;
    }

    //WhoWhat does not exist as a column on the comments table however you can now call it on the model like so:
    //
    //$results = Comment::find(1);
    //$results->who_what;
    // Useful when seperate columns (first name, lastname) and combine them
    public function getWhoWhatAttribute()
    {
        return "user {$this->user_id} rates {$this->rating}";
    }

    // Updates value of attribute before saving
    // $result = Comment::find(1);
    // $result->rating = 4;
    // $result->save();
    public function setRatingAttribute($value)
    {
        $this->attributes['rating'] = $value + 1;
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function country()
    {
        return $this->hasOneThrough('App\Address', 'App\User', 'id', 'user_id', 'user_id')->select('country as name');
    }
}
