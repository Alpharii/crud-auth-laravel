<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $req)
    {
        // 1. Validasi
        $data = $req->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|string|min:6|confirmed',
        ]);

        // 2. Buat user baru
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // 3. Buat token (optional: bisa kasih abilities di argumen kedua)
        $token = $user->createToken('api-token')->plainTextToken;

        // 4. Kembalikan response JSON
        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    // LOGIN
    public function login(Request $req)
    {
        // 1. Validasi input
        $creds = $req->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // 2. Coba autentikasi
        if (! Auth::attempt($creds)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // 3. Ambil user dan buat token baru
        $user  = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        // 4. Kembalikan response JSON
        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }
}
