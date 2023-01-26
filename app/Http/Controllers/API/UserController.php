<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserProfile\CreateAccountRequest;
use App\Http\Requests\API\UserProfile\ProfileUpdateRequest;
use App\Http\Requests\API\UserProfile\RegisterRequest;
use App\Models\RegisterAccessToken;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            RegisterAccessToken::where('phone', $request->phone)->delete();
            $token=Str::random(40);
            $otp=rand(1000,9999);
            RegisterAccessToken::create([
                'phone' => $request->phone,
                'token' => $token,
                'otp' => $otp
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Registration token created successfully',
                'data' => ['token'=>$token,'otp'=>$otp]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'something went wrong'
                /*'message' => $th->getMessage()*/
            ], 500);
        }
    }

    public function createAccount(CreateAccountRequest $request)
    {
        try {
            $auth_info=RegisterAccessToken::where('token', $request->token)->first();

            if (!$auth_info || $auth_info->otp!=$request->otp){
                return response()->json([
                    'status' => false,
                ], 401);
            }
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $input['phone'] = $auth_info->phone;
            $input['role_id'] = 3;
            if($files=$request->file('image')){
                $image = rand() . $files->getClientOriginalName();
                $files->move(public_path('/images/user'), $image);
                $input['image'] = $image ;
            }
            else{
                unset($input['image']);
            }

            $user=User::create($input);
            Auth::login($user);

            $auth_info->delete();
            return response()->json([
                'status' => true,
                'message' => 'User Created and Logged In Successfully',
                'access_token' => $user->createToken('auth_token',['*'],now()->addMinutes(180))->plainTextToken,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'something went wrong'
                /*'message' => $th->getMessage()*/
            ], 500);
        }
    }

    public function userProfile(){
        try {
            $user_info=User::find(Auth::user()->id);
            /*$image_path= baseUrl().'/images/user/'.$user_info->image;*/
            return response()->json([
                'status' => true,
                'data' => $user_info
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'something went wrong'
            ], 500);
        }
    }

    public function updateProfile(ProfileUpdateRequest $request){
        try {
            $input = $request->all();
            if(!empty($input['password'])){
                $input['password'] = Hash::make($input['password']);
            }
            else{
                unset($input['password'],$input['password_confirmation']);
            }

            if($files=$request->file('image')){
                $filename = public_path() . '/images/user/' . Auth::user()->image;
                \File::delete($filename);
                $image = rand() . $files->getClientOriginalName();
                $files->move(public_path('/images/user'), $image);
                $input['image'] = $image ;
            }
            else{
                unset($input['image']);
            }

            User::find(Auth::user()->id)->update($input);

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => User::where('phone',$request->phone)->first()
            ], 200);
        } catch (\Throwable $th) {/*dd($th->getMessage());*/
            return response()->json([
                'status' => false,
                'message' => 'something went wrong'
            ], 500);
        }
    }
}
