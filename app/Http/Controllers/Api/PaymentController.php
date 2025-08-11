<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\MembershipUser;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payment = Payment::all();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil di temukan',
            'data' => $payment,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_membership' => 'required|exists:membership,id',
            'metode_pembayaran' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $membership = Membership::find($request->id_membership);

        if (!$membership) {
            return response()->json([
                'status' => false,
                'message' => 'Membership tidak ditemukan',
            ], 404);
        }

        // Cek membership reguler aktif (bukan harian)
        $activeMembership = MembershipUser::where('id_user', $user->id)
            ->where('status', 'aktif')
            ->where('tanggal_berakhir', '>=', Carbon::now())
            ->where('jenis_membership', '<>', 'daily')
            ->first();

        // Cek booking harian aktif user (tanggal booking hari ini atau besok)
        $bookingHarianAktif = MembershipUser::where('id_user', $user->id)
            ->where('jenis_membership', 'daily')
            ->where('status', 'aktif')
            ->whereDate('tanggal_booking', '>=', Carbon::today())
            ->first();

        if (strtolower($membership->type) === 'daily') {
            // Booking harian, user tidak boleh punya membership reguler aktif
            if ($activeMembership) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kamu tidak bisa booking membership harian karena sudah punya membership aktif hingga '
                                . Carbon::parse($activeMembership->tanggal_berakhir)->format('d-m-Y') . '.',
                ], 403);
            }

            // Kalau sudah booking harian untuk tanggal booking besok, batalkan
            $bookingBesok = MembershipUser::where('id_user', $user->id)
                ->where('jenis_membership', 'daily')
                ->where('tanggal_booking', Carbon::tomorrow()->format('Y-m-d'))
                ->first();

            if ($bookingBesok) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kamu sudah booking membership harian untuk tanggal '
                                . Carbon::tomorrow()->format('d-m-Y') . '.',
                ], 403);
            }

            // Simpan booking harian
            $tanggalBooking = Carbon::tomorrow();
            $startDate = null;
            $endDate = null;
            $kodeQR = 'BOOK-' . Str::random(10);
            $status = 'aktif';

        } else {
            if ($activeMembership) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kamu masih memiliki membership aktif hingga '
                                . Carbon::parse($activeMembership->tanggal_berakhir)->format('d-m-Y') . '.',
                ], 403);
            }

            if ($bookingHarianAktif) {
                $bookingHarianAktif->status = 'nonaktif';
                $bookingHarianAktif->save();
            }

            $startDate = Carbon::now();
            $endDate = $startDate->copy()->addMonths($membership->durasi);

            $tanggalBooking = null;
            $kodeQR = null;
            $status = 'aktif';
        }

        $rawPrice = $membership->price;
        $numericPrice = (int) str_replace('.', '', $rawPrice);

        $payment = Payment::create([
            'id_user' => $user->id,
            'id_membership' => $membership->id,
            'date' => now(),
            'email' => $user->email,
            'price' => $rawPrice,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        $barcode = 'MEM-' . Str::random(10);

        $membershipUser = MembershipUser::create([
            'id_user' => $user->id,
            'id_pembayaran' => $payment->id,
            'tanggal_mulai' => $startDate,
            'tanggal_berakhir' => $endDate,
            'tanggal_booking' => $tanggalBooking,
            'jenis_membership' => $membership->type,
            'harga' => $numericPrice,
            'kode_barcode' => $barcode,
            'kode_qr' => $kodeQR,
            'status' => $status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pembayaran berhasil dan membership terdaftar',
            'data' => $membershipUser,
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
