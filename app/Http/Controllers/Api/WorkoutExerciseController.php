<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkoutExercise;
use Illuminate\Support\Facades\Validator;

class WorkoutExerciseController extends Controller
{
    public function index()
    {
        $exercises = WorkoutExercise::with('level')->get();
        return response()->json($exercises);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'workout_level_id' => 'required|exists:workout_levels,id',
            'name' => 'required|string|max:255',
            'reps' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $exercise = WorkoutExercise::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Latihan berhasil ditambahkan',
            'data' => $exercise,
        ]);
    }

    public function show($id)
    {
        $exercise = WorkoutExercise::with('level')->findOrFail($id);
        return response()->json($exercise);
    }

    public function update(Request $request, $id)
    {
        $exercise = WorkoutExercise::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'reps' => 'sometimes|string|max:100',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $exercise->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Latihan berhasil diperbarui',
            'data' => $exercise,
        ]);
    }

    public function destroy($id)
    {
        $exercise = WorkoutExercise::findOrFail($id);
        $exercise->delete();

        return response()->json([
            'status' => true,
            'message' => 'Latihan berhasil dihapus',
        ]);
    }
}
