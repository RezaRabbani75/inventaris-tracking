<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with('barang')
                        ->where('user_id', Auth::id())
                        ->latest()
                        ->get();

        return view('peminjaman-saya.index', compact('peminjamans'));
    }

    public function create(Request $request)
    {
        $barang_id = $request->query('barang_id');

        if (!$barang_id) {
            return redirect()->route('katalog.index')
                             ->withErrors(['Silahkan pilih perangkat dari katalog terlebih dahulu sebelum meminjam !']);
        }

        $barang = Barang::findOrFail($barang_id);

        return view('peminjaman-saya.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id'               => 'required|exists:barangs,id',
            'jumlah'                  => 'required|integer|min:1',
            'tujuan_pinjam'           => 'required|string|max:255',
            'tanggal_pinjam'          => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
        ], [
            'tanggal_pinjam.after_or_equal' => 'Tanggal pinjam tidak boleh di masa lalu.',
            'tanggal_kembali_rencana.after_or_equal' => 'Tanggal kembali harus sama atau setelah tanggal pinjam.',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($request->jumlah > $barang->stok_tersedia) {
            return back()->withErrors(['jumlah' => 'Jumlah pinjam melebihi stok yang tersedia (Maksimal: ' . $barang->stok_tersedia . ' unit).'])->withInput();
        }

        Peminjaman::create([
            'user_id'                 => Auth::id(),
            'barang_id'               => $request->barang_id,
            'jumlah'                  => $request->jumlah,
            'tujuan_pinjam'           => $request->tujuan_pinjam,
            'tanggal_pinjam'          => $request->tanggal_pinjam,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'status'                  => 'menunggu', 
        ]);

        return redirect()->route('peminjaman-saya.index')
                         ->with('success', 'Pengajuan peminjaman berhasil dibuat! Silakan tunggu persetujuan dari Admin.');
    }

    /**
     * Mengembalikan barang yang sedang dipinjam (Akan kita bahas di tahap selanjutnya)
     */
    public function kembalikan(Request $request, string $id)
    {
        // Nanti kita isi setelah fitur pengajuan dan persetujuan selesai
    }

    /**
     * Membatalkan pengajuan yang masih berstatus 'menunggu'
     */
    public function batalkan(Request $request, string $id)
    {
        $peminjaman = Peminjaman::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($peminjaman->status !== 'menunggu') {
            return back()->withErrors(['Pengajuan ini sudah diproses dan tidak dapat dibatalkan lagi.']);
        }

        $jumlahBatalHariIni = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'ditolak')
            ->where('pesan_admin', 'Dibatalkan otomatis oleh Peminjam')
            ->whereDate('updated_at', today())
            ->count();

        if ($jumlahBatalHariIni >= 3) {
            return back()->withErrors(['Kamu sudah mencapai batas maksimal membatalkan pengajuan (3 kali) untuk hari ini.']);
        }

        $peminjaman->update([
            'status' => 'ditolak',
            'pesan_admin' => 'Dibatalkan otomatis oleh Peminjam'
        ]);

        return back()->with('success', 'Pengajuan peminjaman berhasil dibatalkan.');
    }
}