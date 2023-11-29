<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function Register(Request $request){
        $data = $request -> json() -> all();
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        if($data["confirmPassword"] != $data['password'] ){
            return response()->json(['message' => 'Xác minh lại mật khẩu'],400);
        }
        try {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'CV'=> false,
            ]);
            return response()->json(['message' => 'User registered successfully'],200);
        }
        catch(\Exception $e){
            return response()->json(['message' => 'User registered thất bại'],400);
        }
    }
}
