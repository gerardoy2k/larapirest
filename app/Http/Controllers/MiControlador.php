<?php

namespace App\Http\Controllers;

use App\MiModelo;
class MiControlador extends Controller
{
    

    function index(){
    	$modelo = new MiModelo();
    	$res = $modelo->saludar("Gerardo");
    	return view("prueba.index",["saludo"=>$res]);
    }

}
