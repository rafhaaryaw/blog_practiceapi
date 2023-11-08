<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class C_auth extends Controller
{
    public function login(Request $request)
    {

        $login = Auth::attempt($request->all());
        try {

            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                $token = $request->user()->createToken('logintoken')->plainTextToken;

                return response()->json([
                    'response_code' => 200,
                    'message' => 'login berhasil',
                    'content' => $user,
                    'token' => $token
                ]);
            } else {
                throw new \Exception('Invalid credentials');
            }
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 404,
                'message' => 'username atau Password Tidak Ditemukan!',
            ]);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            return response()->json([
                'success' => true,
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat aku: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Logout successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal Logout'], 401);
        }
    }
}
