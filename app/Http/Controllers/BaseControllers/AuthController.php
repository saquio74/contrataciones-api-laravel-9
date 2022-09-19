<?php

namespace App\Http\Controllers\BaseControllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function Register(Request $request)
    {
        $data = $request->validate([
            "name" => 'required',
            "email" => "required|email|unique:users",
            "password" => "required|confirmed"
        ]);
        $data['password'] = Hash::make($request->password);
        
        $user = User::create($data);
        $user->token = $user->createToken('authToken')->accessToken;

        return response()->json([
            "user" => $user
        ]);
    }
    public function GetUser()
    {
        return response()->json(auth()->user(),200);
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            "email"=>"email|required",
            "password"=>'required'
        ]);
        if(!auth()->attempt($data)){
            return response()->json(["invalid credentials"],401);
        }
        $user = User::whereEmail(auth()->user()->email)->first();
        $user->token  = $user->createToken('authToken')->accessToken;

        return response()->json([
            "user" => $user
        ]);
    }
}
