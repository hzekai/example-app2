<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use JWTAuth; // 使用 JWT 库

class AuthController extends Controller
{
	// 创建用户并生成对应的 token
    public function create() {
        $data = [
            'name' => 'Cookcyq2',
            'email' => '100862@qq.com',
            'password' => bcrypt('1234567')
        ];
        $user = User::create($data);
        $token = JWTAuth::fromUser($user);
        Log::info(json_encode($user));
        // 返回 token
        return response([
            'token' => $token,
            'token_type' => 'bearer',
            // 过期时间
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function login(Request $request) {
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();
        Log::info(json_encode($user));
        $token = Auth::guard('api')->login($user);
        Log::info('after login success');
        return response([
            'code' => 0,
            'msg' => 'success',
            'token' => $token,
        ]);
    }

    public function logout() {
        auth()->guard('api')->logout();
        return response([
            'code' => 0,
            'msg' => 'logout success!'
        ]);
    }

    public function getProfile() {
        return response([
            'data' => auth()->user()
        ]);
    }
}

