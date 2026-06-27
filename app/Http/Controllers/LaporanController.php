<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk fungsi DB::raw

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

        // Ambil data detail transaksi
        $peminjamans = (clone $query)->with(['user', 'barang'])->latest()->get();

        // ========================================================
        // DATA UNTUK GRAFIK (CHART.JS)
        // ========================================================
        
        // 1. Top 5 Barang Paling Banyak Dipinjam (Berdasarkan jumlah unit)
        $topBarang = (clone $query)->select('barang_id', DB::raw('SUM(jumlah) as total_dipinjam'))
            ->with('barang')
            ->groupBy('barang_id')
            ->orderByDesc('total_dipinjam')
            ->take(5)
            ->get();

        $chartBarangLabels = $topBarang->pluck('barang.nama_barang')->toArray();
        $chartBarangData   = $topBarang->pluck('total_dipinjam')->toArray();

        // 2. Top 5 User Paling Aktif Meminjam (Berdasarkan frekuensi/berapa kali pinjam)
        $topUser = (clone $query)->select('user_id', DB::raw('COUNT(*) as total_transaksi'))
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('total_transaksi')
            ->take(5)
            ->get();

        $chartUserLabels = $topUser->pluck('user.name')->toArray();
        $chartUserData   = $topUser->pluck('total_transaksi')->toArray();

        // Kirim semua variabel ke view
        return view('statistik.index', compact(
            'statistik', 
            'peminjamans', 
            'startDate', 
            'endDate',
            'chartBarangLabels',
            'chartBarangData',
            'chartUserLabels',
            'chartUserData'
        ));
    }
}