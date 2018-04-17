<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\City;
use App\Profile;

class Country extends Model
{
    protected $table = 'countries';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'iatacode', 
    ];

    public function cities()
    {
        return $this->belongsToMany(City::class);
    }

    public function profiles()
    {
        return $this->belongsToMany(Profile::class);
    }
}
