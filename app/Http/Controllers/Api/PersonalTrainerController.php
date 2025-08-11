<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PersonalTrainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonalTrainerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personalTrainer = PersonalTrainer::all();

        return response()->json([
            'status' => true,
            'message' => 'Data personal trainer berhasil di temukan',
            'data' => $personalTrainer,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'sesi' => 'required|string',
            'harga' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $personalTrainer = PersonalTrainer::create([
            'name' => $request->name,
            'sesi' => $request->sesi,
            'harga' => $request->harga,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Membership berhasil ditambahkan!',
            'data' => $personalTrainer,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $personalTrainer = PersonalTrainer::findOrFail($id);

        return response() -> json([
            'status' => true,
            'message' => 'berhasil menemukan id membership',
            'data' => $personalTrainer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'sesi' => 'required|string',
            'harga' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $personalTrainer = PersonalTrainer::findOrFail($id);
        $personalTrainer -> update([
            'name' => $request->name,
            'sesi' => $request->sesi,
            'harga' => $request->harga,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil di Update',
            'data' => $personalTrainer,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $personalTrainer = PersonalTrainer::findOrFail($id);

        $personalTrainer->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}
