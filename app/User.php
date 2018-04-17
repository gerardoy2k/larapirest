<?php

namespace App;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';

    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR = 'false';

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 
        'email', 
        'password',
        'verified',
        'verification_token',
        'admin',
        'register_date',
        'last_connection',
        'last_ip',
        'balance',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
        'verification_token',
    ];

    public function esVerificado()
    {
        return $this->verified == User::USUARIO_VERIFICADO;
    } 

    public function esAdministrador()
    {
        return $this->admin == User::USUARIO_ADMINISTRADOR;
    } 

    public static function generarVerificationToken(){
        return str_random(40);
    }

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function administrator()
    {
        return $this->hasOne('App\Administrator');
    }

    public function model()
    {
        return $this->hasOne('App\Model');
    }

    public function client()
    {
        return $this->hasOne('App\Client');
    }

    public function roles()
    {
    	return $this->belongsToMany(Role::class);
    }
}
