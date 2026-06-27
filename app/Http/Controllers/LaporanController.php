<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menampilkan statistik dan daftar peminjaman berdasarkan filter tanggal.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = Peminjaman::whereBetween('created_at', [
            $startDate . ' 00:00:00', 
            $endDate . ' 23:59:59'
        ]);

        $statistik = [
            'total_pengajuan' => (clone $query)->count(),
            'menunggu'        => (clone $query)->where('status', 'menunggu')->count(),
            'disetujui'       => (clone $query)->where('status', 'disetujui')->count(),
            'dipinjam'        => (clone $query)->where('status', 'dipinjam')->count(),
            'dikembalikan'    => (clone $query)->where('status', 'dikembalikan')->count(),
            'ditolak'         => (clone $query)->where('status', 'ditolak')->count(),
        ];

        $peminjamans = (clone $query)->with(['user', 'barang'])->latest()->get();

        return view('laporan.index', compact('statistik', 'peminjamans', 'startDate', 'endDate'));
    }
}