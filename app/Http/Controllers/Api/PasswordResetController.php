<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\PasswordReset;

class PasswordResetController extends Controller
{
    // Kirim token ke email
    public function forgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if (!User::where('email', $value)->exists()) {
                        $fail('Akun dengan email ini tidak ditemukan.');
                    }
                }
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->email;
        $token = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordReset::updateOrCreate(
            ['email' => $email],
            [
                'email' => $email,
                'token' => $token,
                'created_at' => now()
            ]
        );

        Mail::to($email)->send(new ResetPasswordMail($token));

        return response()->json([
            'status' => true,
            'message' => 'Token reset password telah dikirim ke email Anda.',
            'token' => $token, // hanya untuk testing, boleh dihapus di production
        ]);
    }

    // Proses reset password
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:user,email',
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $reset = PasswordReset::where('email', $request->email)->first();

        if (!$reset || $reset->token !== $request->token) {
            return response()->json([
                'status' => false,
                'message' => 'Token tidak valid atau sudah digunakan.'
            ], 400);
        }

        if (now()->diffInMinutes($reset->created_at) > 15) {
            return response()->json([
                'status' => false,
                'message' => 'Token sudah kadaluarsa.'
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus data reset pakai query builder
        PasswordReset::where('email', $request->email)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Password berhasil direset.'
        ]);
    }

}
