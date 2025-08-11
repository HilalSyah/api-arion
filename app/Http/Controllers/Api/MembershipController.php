<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $membership = Membership::all();

        return response()->json([
            'status' => true,
            'message' => 'Data membership berhasil di temukan',
            'data' => $membership,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:100',
            'durasi' => 'required|integer|min:1',
            'satuan' => 'required|string|max:20',
            'price' => 'required',
            'status' => 'required|string|in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $membership = Membership::create([
            'type' => $request->type,
            'durasi' => $request->durasi,
            'satuan' => $request->satuan,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Membership berhasil ditambahkan!',
            'data' => $membership,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $membership = Membership::findOrFail($id);

        return response() -> json([
            'status' => true,
            'message' => 'berhasil menemukan id membership',
            'data' => $membership,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:100',
            'durasi' => 'required|integer|min:1',
            'satuan' => 'required|string|max:20',
            'price' => 'required',
            'status' => 'required|string|in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $membership = Membership::findOrFail($id);
        $membership -> update([
            'type' => $request->type,
            'durasi' => $request->durasi,
            'satuan' => $request->satuan,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil di Update',
            'data' => $membership,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $membership = Membership::findOrFail($id);

        $membership->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus'
        ], 200);

    }
}
