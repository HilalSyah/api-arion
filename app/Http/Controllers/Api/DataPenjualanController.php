<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter month dan year dari query string, default bulan & tahun sekarang
        $month = $request->query('month', now()->month);
        $year = $request->query('year', now()->year);

        // Tentukan tanggal awal dan akhir bulan
        $startDate = Carbon::create($year, $month, 1)->startOfDay();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        // Ambil data total penjualan per hari dari DB
        $sales = DB::table('payment')
            ->select(
                DB::raw('DAY(date) as day'),
                DB::raw('COUNT(*) as total_sales')
            )
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy(DB::raw('DAY(date)'))
            ->orderBy(DB::raw('DAY(date)'))
            ->get()
            ->keyBy('day'); // supaya gampang akses per hari

        // Buat array tanggal 1 sampai akhir bulan, kalau tidak ada data di hari tertentu isi 0
        $daysInMonth = $startDate->daysInMonth;
        $result = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $result[] = [
                'day' => $day,
                'total_sales' => $sales->has($day) ? $sales->get($day)->total_sales : 0
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Data penjualan bulanan berhasil ditemukan',
            'data' => $result
        ]);
    }


    public function store(Request $request)
    {
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
