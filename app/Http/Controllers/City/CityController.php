<?php

namespace App\Http\Controllers\City;
use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CityController extends ApiController
{
    public function __construct()
    {
        // Solo chequea cliente-id, no tiene que loguearse en el sistema
        //$this->middleware('client.credentials')->only(['index']);
        // chequea usuarios autenticados
        $this->middleware('auth:api')->except(['index','citiesByCountryId']);
    }   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::all();
        return $this->showAll($cities);
    }

    public function citiesByCountryId($id)
    {
        $cities = City::where('country_id','=',$id)->get();
        return $this->showAll($cities);
    }
}
