<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PersonalTrainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::with(['trainer', 'personalTrainer'])->get();

        return response()->json([
            'status' => true,
            'message' => 'Data booking berhasil ditemukan',
            'data' => $bookings,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'personal_trainer_id' => 'required|exists:personal_trainer,id',
            'trainer_id' => 'required|exists:list_trainers,id',
            'metode_pembayaran' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $personalTrainer = PersonalTrainer::find($request->personal_trainer_id);
        $price = (int) str_replace('.', '', $personalTrainer->harga);

        $booking = Booking::create([
            'user_id' => $user->id,
            'personal_trainer_id' => $personalTrainer->id,
            'trainer_id' => $request->trainer_id,
            'price' => $price,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Booking berhasil dibuat',
            'data' => $booking,
        ], 201);
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
