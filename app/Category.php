<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Subcategory;

class Category extends Model
{
    protected $table = 'categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
    ];

    public function modelos()
    {
    	return $this->belongsToMany(Modelo::class);
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
}
