<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Log;
use JWTAuth;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    // 创建用户并生成对应的 token
    public function create() {
        $user = new Member();
        $user->phone = '+8201058625673';
        $user->password = bcrypt('12345678');
        $user->email = 'feng@gmail.com';
        $user->nick_name = 'feng';
        $user->account = 'fengac';
        $user = $user->save();
        Log::info(json_encode($user)); // boolean
        $user = Member::first(); // model object
        Log::info(json_encode($user));
        $token = JWTAuth::fromUser($user);
        // 返回 token
        return response([
            'token' => $token,
            'token_type' => 'bearer',
            // 过期时间
            // 'expires_in' => auth()->factory()->getTTL() * 60
            // 命令行能执行, 这里会报错
            // 'expires_in' => Auth::guard('api2')->factory()->getTTL()
        ]);
    }

    public function login(Request $request) {
        $phone = $request->phone;
        $password = $request->password;

        $user = Member::where('phone', $phone)->first();
        Log::info($phone . ', ' . $password);
        Log::info(json_encode($user));
        $token = Auth::guard('api2')->login($user);
        Log::info('after login success');
        return response([
            'code' => 0,
            'msg' => 'success',
            'token' => $token,
        ]);
    }

    // public function getProfile(Request $request) {
    //     $user = Member::first();
    //     return response([
    //         'code' => 0,
    //         'msg' => 'success',
    //         'data' => $user
    //     ]);
    // }

    public function logout() {
        auth()->guard('api2')->logout();
        return response([
            'code' => 0,
            'msg' => 'logout success!'
        ]);
    }

    public function getProfile() {
        return response([
            'data' => auth()->guard('api2')->user()
        ]);
    }
}
