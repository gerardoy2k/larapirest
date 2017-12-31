<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MiModelo extends Model
{
    function saludar($nombre){
        return "Hola ".$nombre;
    }
}
