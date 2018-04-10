<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = 'rates';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'modelo_id', 
        'service_id', 
        'tokens_count', 
    ];

    public function modelo()
    {
        return $this->belongsTo('App\Modelo');
    }

    public function services()
    {
    	return $this->hasMany(Service::class);
    }
}
