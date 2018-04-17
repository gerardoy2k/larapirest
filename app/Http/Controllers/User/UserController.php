<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;
use App\User;
use Mail;

class UserController extends ApiController
{
    public function __construct()
    {
        // Solo chequea cliente-id, no tiene que loguearse en el sistema
        //$this->middleware('client.credentials')->only(['store']); // permite crear usuario sin login
        // chequea usuarios autenticados
        //$this->middleware('auth:api')->except(['login','register','update']);
    }

    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){ 

        $this->validate($request, [
            'email' => 'required|
                        string|
                        email',
            'password' => 'required|
                           string|
                           min:6',
        ]);
        if(Auth::attempt(['email' => request('email'), 
                          'password' => request('password'), 
                          'verified' => 1])){ 
            $user = Auth::user(); 
            $success['status'] = true;
            $success['message'] = 'Login successfully';
            $success['token'] =  $user->createToken('Chatsex')->accessToken; 
            return $this->showResponse($success,200);
        } 
        else{ 
            return $this->errorResponse('Unauthorised', 401); 
        } 
    }

    public function register(Request $request) 
    { 
        $this->validate($request, [
            'nickname' => 'required',
            'email' => 'required|
                        email',
            'password' => 'required|
                           string|
                           min:6',
            'c_password' => 'required|same:password',
        ]);
        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $input['register_date'] = now();
        $input['balance'] = 0;
        $input['verification_token'] = User::generarVerificationToken();
        $input['verified'] = 0;
        try{
            $user = User::create($input); 
            $userResponse['nickname'] = $user->nickname; 
            $userResponse['email'] = $user->email;
            $userResponse['register_date'] = $user->register_date;
        } catch (\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return $this->errorResponse('email already exists', 422); 
            }
        }
        // enviamos correo de confirmacion de email
/*        Mail::send('email.verify', $input['verification_token'], function($message) {
            $message->to(Input::get('email'), Input::get('username'))
                ->subject('Verify your email address');
        });*/
        $success['status'] = true;
        $success['message'] = 'user created successfully';
        $success['user'] = $userResponse;
        return $this->showResponse($success,201); 
    }
    // confirmamos el usuario con el email enviado
    public function confirm($confirmation_code)
    {
        if(!$confirmation_code)
        {
            throw new InvalidConfirmationCodeException;
        }
        $user = User::whereConfirmationCode($confirmation_code)->first();
        if (!$user)
        {
            throw new InvalidConfirmationCodeException;
        }
        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();
        $userResponse['nickname'] = $user->nickname; 
        $userResponse['email'] = $user->email;
        $userResponse['register_date'] = $user->register_date;
        $success['status'] = true;
        $success['message'] = 'email verification successfully';
        $success['user'] = $userResponse;
        return $this->showResponse($success,201); 
    } 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();
        return $this->showAll($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user = User::with('profile')->findOrFail($user->id);
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::FindOrFail($id);
        $user->verified = 1;
        $user->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
