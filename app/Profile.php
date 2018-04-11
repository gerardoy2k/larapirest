<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'lastname',
        'birthdate',
        'genero',
        'country',
        'state',
        'city',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}