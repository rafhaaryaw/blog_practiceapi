<?php

namespace App\Http\Controllers;

use App\Models\blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class C_admin extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();

            if ($user) {
                $user = User::all();
            } else {
                return new \Exception('Gagal Mencari Data');
            }
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function blog()
    {
        try {
            $blog = blog::all();

            return response()->json($blog);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Tidak dapat menemukan blog']);
        }
    }

    public function activation(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $user->update([
                'status' => 'aktif'
            ]);

            return response()->json(['message' => 'akun sudah aktif', 'data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal aktivasi ' . $e->getMessage()], 500);
        }
    }

    public function nonactivation(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $user->update([
                'status' => 'non'
            ]);

            return response()->json(['message' => 'akun sudah dinonaktifkan', 'data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal aktivasi ' . $e->getMessage()], 500);
        }
    }
}
