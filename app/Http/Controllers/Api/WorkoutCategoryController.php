<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkoutCategory;
use Illuminate\Support\Facades\Validator;

class WorkoutCategoryController extends Controller
{
    public function index()
    {
        $data = WorkoutCategory::with('levels.exercises')->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/Images/workout', $filename);
            $data['image'] = 'storage/Images/workout/' . $filename;
        }

        $category = WorkoutCategory::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $category,
        ]);
    }

    public function show($id)
    {
        $category = WorkoutCategory::with('levels.exercises')->findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = WorkoutCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'  => 'sometimes|string|max:255',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/Images/workout', $filename);
            $data['image'] = 'storage/Images/workout/' . $filename;
        }

        $category->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Kategori berhasil diperbarui',
            'data' => $category,
        ]);
    }

    public function destroy($id)
    {
        $category = WorkoutCategory::findOrFail($id);
        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Kategori berhasil dihapus',
        ]);
    }
}
