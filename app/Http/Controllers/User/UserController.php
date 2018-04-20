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
        //$this->middleware('auth:api')->except(['login','register','update','confirm']);
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
                          'password' => request('password')/*, 
                          'verified' => 1*/])){ 
            $user = Auth::user(); 
            $success['status'] = true;
            $success['message'] = 'Login successfully';
            $success['token'] =  "kjhgdjhfkgkiy987687/)(i9pohiugoygyug88898hiuh76786r6;;l;l'plpl'pp]]=l]ll=l]=l=l0l-0-l]]o;0o0o-0[ii;-0i-0i98y75r8j8u5r6f76f876tj876t876t876h7687juihij987987t76r856t8768768678769809jijliuhkyf56u58g76gi7y9";//$user->createToken('Chatsex')->accessToken; 
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
        $userResponse = null;
        $userResponse = User::where('email','=',$input['email'])->first();
        if (!is_null($userResponse))
            return $this->errorResponse('email already exists', 422); 

        $user = User::create($input); 
        $userResponse['nickname'] = $user->nickname; 
        $userResponse['email'] = $user->email;
        $userResponse['register_date'] = $user->register_date;
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
