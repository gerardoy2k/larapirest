<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'iatacode', 
        'country_id',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function profiles()
    {
        return $this->belongsToMany(Profile::class);
    }
}
