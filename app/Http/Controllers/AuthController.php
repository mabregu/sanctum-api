<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $token = $user->createToken(env('APP_TOKEN'))->plainTextToken;

            return response()->json([
                'status' => 'success',
                'data' => $user,
                'token' => $token,
            ]);
        }

        return redirect()->back()->withInput()->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return redirect('/');
    }

    public function register(Request $request)
    {
        $validateData = $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'password' => bcrypt($validateData['password']),
        ]);

        $token = $user->createToken(env('APP_TOKEN'))->plainTextToken;

        return response()->json(['token' => $token], 200);
    }
}
