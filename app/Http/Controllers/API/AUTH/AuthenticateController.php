<?php

namespace App\Http\Controllers\API\AUTH;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\AUTH\ForgotPasswordRequest;
use App\Http\Requests\API\AUTH\LoginRequest;
use App\Http\Requests\API\AUTH\ResetPasswordRequest;
use App\Models\RegisterAccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthenticateController extends Controller
{

    public function login(LoginRequest $request)
    {
        try {
            $user= User::Where('email',$request->identifier)->first();
            /*----------------------for email login------------------------*/
            if ($user && $user->role_id==3){
                $credentials=['email' => $request->identifier, 'password' => $request->password,];
                if(!Auth::attempt($credentials)){
                    return response()->json([
                        'status' => false,
                        'message' => 'These credentials do not match our records.',
                    ], 401);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'User logged in successfully',
                    'data' =>['token' => $user->createToken('auth_token',['*'],now()->addMinutes(180))->plainTextToken,],
                ], 200);
            }
            /*----------------------for phone login------------------------*/
            $user= User::Where('phone',$request->identifier)->first();
            if ($user && $user->role_id==3){
                $credentials=['email' => $user->email, 'password' => $request->password,];
                if(!Auth::attempt($credentials)){
                    return response()->json([
                        'status' => false,
                        'message' => 'These credentials do not match our records.',
                    ], 401);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'User logged in successfully',
                    'data' =>['token' => $user->createToken('auth_token',['*'],now()->addMinutes(180))->plainTextToken,],
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'These credentials do not match our records.',
            ], 401);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'something went wrong!'/*$th->getMessage()*/
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::user()->tokens()->where('id', Auth::user()->currentAccessToken()->id)->delete();
            return response()->json([
                'status' => true,
                'message' => 'User logged out successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'something went wrong!'/*$th->getMessage()*/
            ], 500);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $user_info=User::where('phone', $request->phone)->first();
            if (!$user_info){
                return response()->json([
                    'status' => false,
                    'message' => 'User not found!'
                ], 404);
            }
            RegisterAccessToken::where('phone', $request->phone)->delete();
            $token=Str::random(40);
            $otp=rand(1000,9999);
            $forgot_credentials=RegisterAccessToken::create([
                'phone' => $request->phone,
                'token' => $token,
                'otp' => $otp
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Forgot password token generated successfully',
                'data' => ['token'=>$token,'otp'=>$otp],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'something went wrong!'/*$th->getMessage()*/
            ], 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $auth_info=RegisterAccessToken::where('token', $request->token)->first();
            if (!$auth_info || $auth_info->otp!=$request->otp){
                return response()->json([
                    'status' => false,
                    'message' => 'Token or otp mismatch.'
                ], 401);
            }
            $user=User::where('phone', $auth_info->phone)->first();
            $user->password = Hash::make($request->password);
            $user->update();
            $auth_info->delete();
            return response()->json([
                'status' => true,
                'message' => 'Password reset successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'something went wrong!'/* $th->getMessage()*/
            ], 500);
        }
    }

    public function updateFcm(Request $request){
        try {
            $request->validate([
                'token' => 'required',
            ]);
            User::find(Auth::user()->id)->update(['fcm_token'=>$request->token]);
            return response()->json([
                'status' => true,
                'message' => 'Successfully updated'
            ], 200);
        }catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => 'something went wrong!'/* $th->getMessage()*/
            ], 500);
        }
    }
}
