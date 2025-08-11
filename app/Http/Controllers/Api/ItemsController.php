<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemsController extends Controller
{
    public function index()
    {
        $items = Items::with('category', 'details')->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            $request->image->storeAs('public/Images/menu', $imageName);
            $validated['image'] = 'storage/Images/menu/' . $imageName;
        }

        $item = Items::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Item berhasil ditambahkan',
            'data' => $item,
        ], 201);
    }

    public function show(string $id)
    {
        $item = Items::with('category', 'details')->findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, string $id)
    {
        $item = Items::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            $request->image->storeAs('public/Images/menu', $imageName);
            $validated['image'] = 'storage/Images/menu/' . $imageName;
        }

        $item->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Item berhasil diperbarui',
            'data' => $item,
        ]);
    }


    public function destroy(string $id)
    {
        $item = Items::findOrFail($id);
        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'Item berhasil dihapus',
        ]);
    }
}
