<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = new User();
        $user->name = 'admin';
        $user->email = 'admin@example.com';
        $user->password = Hash::make('admin');
        $user->save();
        return $user->toJson();
        // $request->validate([
        //     'name' => 'required',
        //     'password' => 'required',
        // ]);
        // if (Auth::attempt($request->only('name', 'password'))) {
        //     $user = Auth::user();
        //     $token = $user->createToken('auth-token')
        //                    ->plainTextToken;
        //     return response()->json(['token' => $token], 200);
        // }
        // throw ValidationException::withMessages([
        //     'name' => ['The provided credentials are incorrect.'],
        // ]);
    }
}