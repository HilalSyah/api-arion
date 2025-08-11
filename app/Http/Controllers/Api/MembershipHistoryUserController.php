<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MembershipUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipHistoryUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $user = Auth::user();

        $membership_user = MembershipUser::where('id_user', $user->id)->get();

        foreach ($membership_user as $membership) {
            if ($membership->tanggal_berakhir && Carbon::now()->gt(Carbon::parse($membership->tanggal_berakhir))) {
                $membership->status = 'nonaktif';
                $membership->save();
            }

            if (
                strtolower($membership->jenis_membership) === 'harian' &&
                $membership->tanggal_booking &&
                Carbon::now()->toDateString() > Carbon::parse($membership->tanggal_booking)->toDateString()
            ) {
                $membership->status = 'nonaktif';
                $membership->save();
            }
        }

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
