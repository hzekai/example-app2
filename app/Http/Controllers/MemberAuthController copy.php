<?php

namespace App\Http\Controllers\Api\V1\Customer\Auth;

use App\CentralLogics\Helpers;
use App\CentralLogics\SMS_module;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Http\Resources\RequestMoneyResource;
use App\Http\Resources\TransactionResource;
use App\Models\Banner;
use App\Models\EMoney;
use App\Models\LinkedWebsite;
use App\Models\PhoneVerification;
use App\Models\BusinessSetting;
use App\Models\Purpose;
use App\Models\RequestMoney;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Member;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;


class MemberAuthController extends Controller
{
    // 创建用户并生成对应的 token
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'account' => 'required',
            'nick_name' => 'required',
            'phone' => 'required|unique:users|min:5|max:20',
            'email' => '',
            'password' => 'required|min:8|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $verify = null;
        if(Helpers::get_business_settings('phone_verification') == 1) {
            if($request->has('otp')) {
                $verify = PhoneVerification::where(['phone' => $request['phone'], 'otp' => $request['otp']])->first();
                if (!isset($verify)) {
                    return response()->json(['errors' => [
                        ['code' => 'otp', 'message' => 'OTP is not found!']
                    ]], 404);

                }
            }else{
                return response()->json(['errors' => [
                    ['code' => 'otp', 'message' => 'OTP is required.']
                ]], 403);
            }
        }

        $user = Member::where('phone', $request->phone)->first();
        if (isset($user)) {
            return response()->json(['message' => 'user registered'], 403);
        }

        DB::transaction(function () use ($request, $verify) {
            if(isset($verify)) {
                $verify->delete();
            }

            $member = new Member();
            $member->account = $request->account;
            $member->nick_name = $request->nick_name;
            $member->level = 0;
            $member->phone = Helpers::filter_phone($request->phone);
            $member->email = $request->email;
            $member->password = bcrypt($request->password);
            $member->save();

            $emoney = new EMoney();
            $emoney->user_id = $member->id;
            $emoney->save();
        });

        return response()->json(['message' => 'Registration Successful'], 200);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|unique:users|min:5|max:20',
            'password' => 'required|min:8|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $phone = $request->phone;
        $password = $request->password;

        $user = Member::where('phone', $phone)->first();
        if (!isset($user)) {
            return response()->json(['message' => 'user not exists'], 403);
        }

        $token = Auth::guard('api')->login($user);
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
            'data' => auth()->guard('api')->user()
        ]);
    }
}
