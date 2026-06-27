<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerusakan;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanKerusakanController extends Controller
{
    public function index()
    {
        $laporans = LaporanKerusakan::with(['barang', 'peminjaman'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('laporan-kerusakan.index', compact('laporans'));
    }

    public function create(Request $request)
    {
        $peminjaman_id = $request->query('peminjaman_id');
        $peminjaman = null;
        $barangs = [];

        if ($peminjaman_id) {
            $peminjaman = Peminjaman::where('id', $peminjaman_id)
                                    ->where('user_id', Auth::id())
                                    ->with('barang')
                                    ->firstOrFail();
        } else {
            $barangs = Barang::where('total_stok', '>', 0)->get();
        }

        return view('laporan-kerusakan.create', compact('peminjaman', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id'           => 'required|exists:barangs,id',
            'peminjaman_id'       => 'nullable|exists:peminjamans,id',
            'jumlah_rusak'        => 'required|integer|min:1',
            'deskripsi_kerusakan' => 'required|string|max:500',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($request->filled('peminjaman_id')) {
            $peminjaman = Peminjaman::where('id', $request->peminjaman_id)
                                    ->where('user_id', Auth::id())
                                    ->firstOrFail();

            if ($request->jumlah_rusak > $peminjaman->jumlah) {
                return back()->withErrors([
                    'jumlah_rusak' => 'Jumlah barang rusak (' . $request->jumlah_rusak . ' unit) tidak boleh melebihi jumlah yang kamu pinjam (' . $peminjaman->jumlah . ' unit).'
                ])->withInput();
            }
        } else {
            if ($request->jumlah_rusak > $barang->stok_tersedia) {
                return back()->withErrors([
                    'jumlah_rusak' => 'Jumlah barang rusak melebihi stok yang tersedia saat ini di rak lab (Maksimal: ' . $barang->stok_tersedia . ' unit).'
                ])->withInput();
            }
        }

        // 1. Simpan laporan kerusakan ke database
        LaporanKerusakan::create([
            'user_id'             => Auth::id(),
            'barang_id'           => $request->barang_id,
            'peminjaman_id'       => $request->peminjaman_id,
            'jumlah_rusak'        => $request->jumlah_rusak,
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'status'              => 'menunggu_tinjauan',
            'catatan_petugas'     => null
        ]);

        // 2. Kirim Notifikasi otomatis ke seluruh Superadmin dan Teknisi
        $staffs = User::role(['superadmin', 'technician'])->get();
        foreach ($staffs as $staff) {
            $staff->notify(new GeneralNotification(
                'Laporan Kerusakan Baru',
                Auth::user()->name . ' melaporkan kerusakan ' . $barang->nama_barang . ' sebanyak ' . $request->jumlah_rusak . ' unit.',
                url('kelola-perbaikan'), // Diarahkan ke halaman daftar perbaikan milik admin/teknisi
                'Cek Laporan',
                'danger' // Menggunakan tipe danger (merah) karena ini laporan kerusakan
            ));
        }

        return redirect()->route('laporan-kerusakan.index')
                         ->with('success', 'Laporan kerusakan berhasil dikirim. Teknisi dan Admin akan segera memeriksa perangkat !');
    }
}