<?php

namespace App\Http\Controllers\AUTH;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class AuthenticateController extends Controller
{
    public function loginPage()
    {
        return view('auth.login');

    }
    public function webLogin(WebLoginRequest $request)
    {
        try {
            $user=User::where('email',$request->email)->first();
            $credentials = $request->only('email','password');
            if (Auth::attempt($credentials) && $user->role_id != 3) {
                return redirect(route('Dashboard'));
            }
            return Redirect::back()->withErrors(['email' => 'These credentials do not match our records.']);
        }catch (\Throwable $th){
            //dd($th);
            return Redirect::back()->withErrors(['email' =>'Something Went Wrong.']);
        }
    }

    public function webLogout(Request $request)
    {
        Auth::logout();
        return redirect(route('LoginPage'));
    }
}
