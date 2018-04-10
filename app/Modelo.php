<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $table = 'modelos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'age', 
        'body_type',
        'weight',
        'height',
        'color_eye',
        'about_me',
        'about_show',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function categories()
    {
    	return $this->belongsToMany(Category::class);
    }

    public function rates()
    {
    	return $this->hasMany(Rate::class);
    }

    public function videos()
    {
    	return $this->hasMany(Video::class);
    }
}
