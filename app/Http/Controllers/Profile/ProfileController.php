<?php

namespace App\Http\Controllers\Profile;

use App\Profile;
use App\Country;
use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class ProfileController extends ApiController
{
    public function __construct()
    {
        // Solo chequea cliente-id, no tiene que loguearse en el sistema
        //$this->middleware('client.credentials')->only(['index']);
        // chequea usuarios autenticados
        //$this->middleware('auth:api')->except(['index']);
    }   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas = [
            'name' => 'required', // Requerido
            'lastname' => 'required', // Requerido
            'birthdate' => 'required', // Requerido
            'gender' => 'required', // Requerido
            'user_id' => 'required'
        ];
        $this->validate($request, $reglas);  // validamos con las reglas. si no valida arrojamos excepcion
        $campos = $request->all(); // extraemos todos los valores del request
        $profile = null;     
        // Verificamos si el pais y la ciudad existen   
        if ($request->has('country_id')){
            if (!Country::find($campos['country_id']))
                return $this->errorResponse('Country does not exists',400);
        }
        if ($request->has('city_id')){
            if (!City::find($campos['city_id']))
                return $this->errorResponse('City does not exists',400);
        }
        try{
            $profile = Profile::create($campos);
        } catch (\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){  // si ya existe el profile del user entonces lo actualizo
                $profile = Profile::where('user_id','=','2')->first();
                if ($request->has('name'))
                    $profile->name = $request->name;
                if ($request->has('lastname'))
                    $profile->lastname = $request->lastname;
                if ($request->has('birthdate'))
                    $profile->birthdate = $request->birthdate;
                if ($request->has('gender'))
                    $profile->gender = $request->gender;    
                if ($request->has('country_id'))
                    $profile->country_id = $request->country_id;
                if ($request->has('city_id'))
                    $profile->city_id = $request->city_id;
                if ($request->has('state'))
                    $profile->state = $request->state;
                if ($request->has('phone'))
                    $profile->phone = $request->phone;
                $profile->save();
            }
        }
        return $this->showOne($profile, 201);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        return $this->showOne($profile);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        // Verificamos si el pais y la ciudad existen   
        if ($request->has('country_id')){
            if (!Country::find($request->country_id))
                return $this->errorResponse('Country does not exists',400);
        }
        if ($request->has('city_id')){
            if (!City::find($request->city_id))
                return $this->errorResponse('City does not exists',400);
        }
        if ($request->has('name'))
            $profile->name = $request->name;
        if ($request->has('lastname'))
            $profile->lastname = $request->lastname;
        if ($request->has('birthdate'))
            $profile->birthdate = $request->birthdate;
        if ($request->has('gender'))
            $profile->gender = $request->gender;    
        if ($request->has('country_id'))
            $profile->country_id = $request->country_id;
        if ($request->has('city_id'))
            $profile->city_id = $request->city_id;
        if ($request->has('state'))
            $profile->state = $request->state;
        if ($request->has('phone'))
            $profile->phone = $request->phone;
        if (!$profile->isDirty())  // Si el objeto no ha sido modificado en alguno de sus valores  // 422 Malformed Request
            return $this->errorResponse('Se debe especificar al menos un valor diferente para modificar ', 422);  
        $profile->save();
        return $this->showOne($profile);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
