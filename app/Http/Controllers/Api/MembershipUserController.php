<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MembershipUser;
use Illuminate\Http\Request;

class MembershipUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $membership_user = MembershipUser::all();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil di temukan',
            'data' => $membership_user,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
