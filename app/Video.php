<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'extension',
        'size',
        'description',
        'tags',
    ];

    public function modelo()
    {
        return $this->belongsTo('App\Modelo');
    }
}
