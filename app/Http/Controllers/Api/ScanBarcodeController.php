<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Leaderboard;
use App\Models\MembershipUser;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScanBarcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        // Cari member berdasarkan barcode
        $member = MembershipUser::where('kode_barcode', $request->barcode)->first();

        if (!$member) {
            return response()->json([
                'status' => false,
                'message' => 'Barcode tidak valid',
            ], 404);
        }

        $userId = $member->id_user;
        $today = Carbon::today();

        // Cek apakah user sudah absen hari ini
        $alreadyAttended = Attendance::where('id_user', $userId)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($alreadyAttended) {
            return response()->json([
                'status' => false,
                'message' => 'Kamu sudah absen hari ini.',
            ], 409);
        }

        // Simpan absensi ke DB
        $attendance = Attendance::create([
            'id_user' => $userId,
            'tanggal' => now(),
        ]);

        // Tambahkan poin ke leaderboard
        $leaderboard = Leaderboard::firstOrCreate(
            ['id_user' => $userId],
            ['points' => 0]
        );

        $leaderboard->increment('points', 10);

        return response()->json([
            'status' => true,
            'message' => 'Absensi berhasil dan poin ditambahkan!',
            'data' => [
                'user_id' => $userId,
                'tanggal_absen' => $attendance->tanggal->toDateTimeString(),
                'point_total' => $leaderboard->points,
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
