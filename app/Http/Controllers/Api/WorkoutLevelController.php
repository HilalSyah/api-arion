<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkoutLevel;
use Illuminate\Support\Facades\Validator;

class WorkoutLevelController extends Controller
{
    public function index()
    {
        $levels = WorkoutLevel::with('category', 'exercises')->get();
        return response()->json($levels);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'workout_category_id' => 'required|exists:workout_categories,id',
            'name' => 'required|string|max:255',
            'sets_reps' => 'required|string|max:255',
            'note' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $level = WorkoutLevel::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Level berhasil ditambahkan',
            'data' => $level,
        ]);
    }

    public function show($id)
    {
        $level = WorkoutLevel::with('exercises')->findOrFail($id);
        return response()->json($level);
    }

    public function update(Request $request, $id)
    {
        $level = WorkoutLevel::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'sets_reps' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $level->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Level berhasil diperbarui',
            'data' => $level,
        ]);
    }

    public function destroy($id)
    {
        $level = WorkoutLevel::findOrFail($id);
        $level->delete();

        return response()->json([
            'status' => true,
            'message' => 'Level berhasil dihapus',
        ]);
    }
}
