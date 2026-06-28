<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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
        
        $topBarang = (clone $query)->select('barang_id', DB::raw('SUM(jumlah) as total_dipinjam'))
            ->with('barang')
            ->groupBy('barang_id')
            ->orderByDesc('total_dipinjam')
            ->take(5)
            ->get();

        $chartBarangLabels = $topBarang->pluck('barang.nama_barang')->toArray();
        $chartBarangData   = $topBarang->pluck('total_dipinjam')->toArray();

        $topUser = (clone $query)->select('user_id', DB::raw('COUNT(*) as total_transaksi'))
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('total_transaksi')
            ->take(5)
            ->get();

        $chartUserLabels = $topUser->pluck('user.name')->toArray();
        $chartUserData   = $topUser->pluck('total_transaksi')->toArray();

        if ($request->input('export') === 'pdf') {
            $pdf = Pdf::loadView('statistik.pdf', compact('peminjamans', 'statistik', 'startDate', 'endDate'));
            
            $pdf->setPaper('A4', 'landscape');
            
            return $pdf->download('Laporan-Peminjaman-Lab-'.$startDate.'-sd-'.$endDate.'.pdf');
        }

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