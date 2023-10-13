<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->json()->all();

        if (Auth::attempt($credentials)) {
            // Đăng nhập thành công, tạo và trả về token
            $user = Auth::user();
            $currentTime = Carbon::now();// lấy thời gian hiện tại
            $expiresAt = $currentTime-> addHour();// thời gian hết hạn củ token

            $token = $user->createToken('MyAppToken',["*"],$expiresAt)->plainTextToken;
            return response()->json(['token' => $token,'user'=> $user],200);
        }

        // Đăng nhập thất bại
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
