<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trainers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ListTrainerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listTrainers = Trainers::all();

        return response()->json([
            'status' => true,
            'message' => 'Data membership berhasil di temukan',
            'data' => $listTrainers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
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

        $listTrainers = Trainers::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Membership berhasil ditambahkan!',
            'data' => $listTrainers,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $listTrainers = Trainers::findOrFail($id);

        return response() -> json([
            'status' => true,
            'message' => 'berhasil menemukan id membership',
            'data' => $listTrainers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
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

        $listTrainers = Trainers::findOrFail($id);
        $listTrainers -> update([
            'name' => $request->name,
            'gender' => $request->gender,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil di Update',
            'data' => $listTrainers,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $listTrainers = Trainers::findOrFail($id);

        $listTrainers->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}
