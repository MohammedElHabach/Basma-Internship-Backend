<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;

class AuthController extends Controller
{

    public function _construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function register(Request $request){
        //Validate data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(),400);
        }
        
        //Request is valid, create new user
        $user = User::create(array_merge(
            $validator->validated(),
            ['password'=>bcrypt($request->password)]
        ));
        return response()->json(['message'=>'User successfully registered','user'=>$user],201);

    }
 
    public function login(Request $request){
        //Validate data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50',
        ]);

         //Send failed response if request is not valid
         if ($validator->fails()) {
            return response()->json($validator->errors(),422);
        }

        if (!($token = auth()->attempt($validator->validated()))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }        
        return $this->createNewToken($token);

    }

    public function createNewToken($token){
        return response()->json([
            'access_token'=>$token,
            'token_type'=>'bearer',
            'expires_in'=>auth()->factory()->getTTL()*60,
            'user'=>auth()->user()
        ]);

    }

    public function profile(){
        return response()->json(auth()->user());
    }

    public function logout(){
        auth()->logout();
        return response()->json(['message'=>'User logged out']);

    }
}
