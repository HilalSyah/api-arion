<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetailsController extends Controller
{
    public function index()
    {
        $details = Details::with('item')->get();
        return response()->json($details);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|exists:items,id',
            'nama' => 'required|string|max:255',
            'jumlahKandunganPer' => 'required|string|max:255',
            'manfaat' => 'required|string',
            'additionalNote' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/Images/detail', $imageName);
            $validated['image'] = 'storage/Images/detail/' . $imageName;
        }

        $detail = Details::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Detail berhasil ditambahkan',
            'data' => $detail,
        ], 201);
    }

    public function show(string $id)
    {
        $detail = Details::with('item')->findOrFail($id);
        return response()->json($detail);
    }

    public function update(Request $request, string $id)
    {
        $detail = Details::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'item_id' => 'sometimes|exists:items,id',
            'nama' => 'sometimes|string|max:255',
            'jumlahKandunganPer' => 'sometimes|string|max:255',
            'manfaat' => 'sometimes|string',
            'additionalNote' => 'nullable|string',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/Images/detail', $imageName);
            $validated['image'] = 'storage/Images/detail/' . $imageName;
        }

        $detail->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Detail berhasil diperbarui',
            'data' => $detail,
        ]);
    }


    public function destroy(string $id)
    {
        $detail = Details::findOrFail($id);
        $detail->delete();

        return response()->json([
            'status' => true,
            'message' => 'Detail berhasil dihapus',
        ]);
    }
}
