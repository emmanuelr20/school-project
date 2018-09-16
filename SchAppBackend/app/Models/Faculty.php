<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
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
        'created_at', 'updated_at'
    ];

    public function departments()
    {
        return $this->hasMany('App\Models\Department');
    }

    public function dean()
    {
        return $this->belongsTo('App\User', 'dean');
    }

    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    public function polls()
    {
        return $this->hasMany('App\Models\Poll');
    }
}
