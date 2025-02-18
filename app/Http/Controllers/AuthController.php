<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // バリデーション
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // バリデーション失敗したら
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // バリデーション成功したら
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            // 'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
}
