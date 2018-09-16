<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        //
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function poll()
    {
        return $this->belongsTo('App\Models\Poll');
    } 

    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }
}
