<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class C_updateprofile extends Controller
{
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            return response()->json(['message' => 'Profil pengguna berhasil diperbarui'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function updatepass(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            if (!Hash::check($request->input('current_password'), $user->password)) {
                return response()->json(['message' => 'Password lama tidak sesuai'], 422);
            }

            $validator = validator($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required'
            ]);

            if ($validator->fails()) {
                throw new ValidationException();
            }

            $user->password = Hash::make($request->input('new_password'));
            $user->save();

            return response()->json(['message' => 'Password berhasil diubah'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
