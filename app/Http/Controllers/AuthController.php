<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->only('email', 'password');
        
        $auth = Auth::attempt($credentials);

        if(!$auth){
            return response()->json([
                'status' => 403,
                'message' => "user not found",
            ]);
        }

        $token = Auth::user()->createToken("api")->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => "login success",
            'token' => $token,
        ]);
    }
}
