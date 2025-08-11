<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Categories::with('items.details')->get();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/Images/menu', $imageName);
            $imagePath = 'storage/Images/menu/' . $imageName;
        }

        $category = Categories::create([
            'name' => $request->name,
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $category,
        ], 201);
    }

    public function show(string $id)
    {
        $category = Categories::with('items.details')->findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, string $id)
    {
        $category = Categories::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            $request->file('image')->storeAs('public/Images/menu', $imageName);
            $validated['image'] = 'storage/Images/menu/' . $imageName;
        }

        $category->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Kategori berhasil diperbarui',
            'data' => $category,
        ]);
    }


    public function destroy(string $id)
    {
        $category = Categories::findOrFail($id);
        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Kategori berhasil dihapus',
        ]);
    }
}
