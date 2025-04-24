<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function actionLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $credential = $request->only('email', 'password');
        if (Auth::attempt($credential)) {
            return redirect('dashboard')->with('success', 'Success Login');
        } else {
            return back()->withErrors(['email' => 'Please Check Your Credential'])->withInput();
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }
}
