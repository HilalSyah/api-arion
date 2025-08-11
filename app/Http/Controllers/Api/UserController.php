<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil di temukan',
            'data' => $user,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'username' => 'required|string|max:255|unique:user,username',
        'email' => 'required|email|unique:user,email',
        'password' => 'required|string|min:6',
        'role' => 'required|string',
        'gender' => 'required|in:male,female,other',
        'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->only(['username', 'email', 'role', 'gender', 'phone']);
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);

        return response()->json([
            'status' => true,
            'message' => 'User berhasil dibuat!',
            'data' => $user,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return response() -> json([
            'status' => true,
            'message' => 'Data berhasil di temukan berdasarkan ID',
            'data' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
        'username' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:user,email,' . $id,
        'password' => 'sometimes|string|min:6',
        'role' => 'sometimes|string',
        'gender' => 'sometimes|in:male,female,other',
        'phone' => 'sometimes|string|max:20', // validasi untuk nomor telepon
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::findOrFail($id);

        // Ambil hanya data yang diizinkan
        $data = $request->only(['username', 'email', 'role', 'gender', 'phone']);

        // Khusus password, hash terlebih dahulu jika ada
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Update data user
        $user->update($data);

        return response()->json([
            'status' => true,
            'message' => 'User berhasil diperbarui!',
            'data' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $user -> delete();

        return response() -> json([
            'status' => true,
            'message' => 'Data berhasil di hapus',
        ]);
    }
}
