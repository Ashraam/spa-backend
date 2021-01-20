<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login()
    {
        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $token = auth()->user()->createToken('authToken')->plainTextToken;

            return response()->json([
                'user' => auth()->user(),
                'token' => $token
            ]);
        }

        throw ValidationException::withMessages([
            'email' => 'Invalid credentials'
        ]);
    }

    public function logout()
    {
        if (auth()->check()) {
            request()->session()->invalidate();
    
            request()->session()->regenerateToken();
    
            Auth::logout();
        }

        return response()->noContent();
    }
}
