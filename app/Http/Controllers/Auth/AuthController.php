<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getProfile($id)
    {
        try {
            $user = User::find($id);
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['mess' => 'sai']);
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $data = $request->json()->all();
        $user = User::find($id);
        if ($user) {
            $user->name = $data['name'];
            $user->address = $data['address'];
            $user->phone = $data['phone'];
            $user->save();
            return response()->json(["mess" => "update thanh cong", "user" => $user]);
        }
        return response()->json(["mess" => "update thanh cong", "user" => $user]);
    }
    public function getAll()
    {
        try {
            $user = User::all();
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['mess' => 'sai']);
        }
    }
}
