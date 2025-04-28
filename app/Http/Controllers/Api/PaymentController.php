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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            // Ambil user dari token login
        $user = $request->user();

        // Validasi manual hanya input yang diisi user
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

        // Ambil data membership untuk ambil price
        $membership = Membership::find($request->id_membership);

        if (!$membership) {
            return response()->json([
                'status' => false,
                'message' => 'Membership tidak ditemukan',
            ], 404);
        }

        // Simpan data payment
        $payment = Payment::create([
            'id_user' => $user->id,
            'id_membership' => $membership->id,
            'date' => now(),
            'email' => $user->email,
            'price' => $membership->price,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        $startDate = Carbon::now();
        $endDate = $startDate->copy();

        if ($membership->durasi == 1) {
            $endDate->addMonth();  // 1 bulan
        } elseif ($membership->durasi == 3) {
            $endDate->addMonths(3);
        } elseif ($membership->durasi == 12) {
            $endDate->addYear();
        }

        $barcode = 'MEM-' . Str::random(10);

        $membershipUser = MembershipUser::create([
            'id_user' => $user->id,
            'id_pembayaran' => $payment->id,
            'tanggal_mulai' => $startDate,
            'tanggal_berakhir' => $endDate,
            'jenis_membership' => $membership->type,
            'harga' => $payment->price,
            'kode_barcode' => $barcode,
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
