<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\MembershipUser;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentAdminController extends Controller
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
        // Validasi input termasuk id_user
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|exists:user,id',
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

        // Ambil user berdasarkan id_user yang diberikan
        $user = User::find($request->id_user);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan',
            ], 404);
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
            ->where('jenis_membership', '<>', 'harian')
            ->first();

        $bookingHarianAktif = MembershipUser::where('id_user', $user->id)
            ->where('jenis_membership', 'harian')
            ->where('status', 'aktif')
            ->whereDate('tanggal_booking', '>=', Carbon::today())
            ->first();

        if (strtolower($membership->type) === 'harian') {
            if ($activeMembership) {
                return response()->json([
                    'status' => false,
                    'message' => 'User sudah punya membership aktif hingga '
                                . Carbon::parse($activeMembership->tanggal_berakhir)->format('d-m-Y') . '.',
                ], 403);
            }

            $bookingBesok = MembershipUser::where('id_user', $user->id)
                ->where('jenis_membership', 'harian')
                ->where('tanggal_booking', Carbon::tomorrow()->format('Y-m-d'))
                ->first();

            if ($bookingBesok) {
                return response()->json([
                    'status' => false,
                    'message' => 'User sudah booking membership harian untuk tanggal '
                                . Carbon::tomorrow()->format('d-m-Y') . '.',
                ], 403);
            }
            $tanggalBooking = Carbon::tomorrow();
            $startDate = null;
            $endDate = null;
            $kodeQR = 'BOOK-' . Str::random(10);
            $status = 'aktif';

        } else {
            if ($activeMembership) {
                return response()->json([
                    'status' => false,
                    'message' => 'User masih memiliki membership aktif hingga '
                                . Carbon::parse($activeMembership->tanggal_berakhir)->format('d-m-Y') . '.',
                ], 403);
            }

            if ($bookingHarianAktif) {
                $bookingHarianAktif->status = 'nonaktif';
                $bookingHarianAktif->save();
            }

            $startDate = Carbon::now();
            $endDate = $startDate->copy();

            if ($membership->durasi == 1) {
                $endDate->addMonth();
            } elseif ($membership->durasi == 3) {
                $endDate->addMonths(3);
            } elseif ($membership->durasi == 6) {
                $endDate->addMonths(6);
            } elseif ($membership->durasi == 12) {
                $endDate->addYear();
            }

            $tanggalBooking = null;
            $kodeQR = null;
            $status = 'aktif';
        }

        $payment = Payment::create([
            'id_user' => $user->id,
            'id_membership' => $membership->id,
            'date' => now(),
            'email' => $user->email,
            'price' => $membership->price,
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
            'harga' => $payment->price,
            'kode_barcode' => $barcode,
            'kode_qr' => $kodeQR,
            'status' => $status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pembayaran berhasil dan membership terdaftar untuk user id ' . $user->id,
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
        $membershipUser = MembershipUser::find($id);

        if (!$membershipUser) {
            return response()->json([
                'status' => false,
                'message' => 'Data membership user tidak ditemukan',
            ], 404);
        }

        // Ambil ID pembayaran terkait dari membership_user
        $paymentId = $membershipUser->id_pembayaran;

        // Hapus data membership_user
        $membershipUser->delete();

        // Jika ada payment terkait, hapus juga
        if ($paymentId) {
            $payment = Payment::find($paymentId);
            if ($payment) {
                $payment->delete();
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Data membership user dan payment terkait berhasil dihapus',
        ]);
    }
}
