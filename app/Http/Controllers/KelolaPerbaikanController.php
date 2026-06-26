<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerusakan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelolaPerbaikanController extends Controller
{
    /**
     * Menampilkan semua daftar laporan untuk Admin dan Teknisi.
     */
    public function index()
    {
        $laporans = LaporanKerusakan::with(['user', 'barang', 'peminjaman'])->latest()->get();
        
        return view('kelola-perbaikan.index', compact('laporans'));
    }

    /**
     * Memproses perubahan status dan perpindahan stok (Dieksekusi oleh Admin / Teknisi)
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status'          => 'required|in:menunggu_tinjauan,sedang_diperbaiki,selesai,rusak_total',
            'catatan_petugas' => 'nullable|string|max:500'
        ]);

        $laporan = LaporanKerusakan::findOrFail($id);
        $barang = Barang::findOrFail($laporan->barang_id);

        $statusLama = $laporan->status;
        $statusBaru = $request->status;

        if ($statusLama === $statusBaru) {
            return back()->with('info', 'Tidak ada perubahan status yang dilakukan.');
        }

        // Membungkus dalam DB Transaction agar jika satu gagal, semua dibatalkan (Safety First)
        DB::beginTransaction();
        try {
            // Transisi 1: Admin menyetujui laporan untuk diperbaiki
            if ($statusLama === 'menunggu_tinjauan' && $statusBaru === 'sedang_diperbaiki') {
                if ($barang->stok_tersedia < $laporan->jumlah_rusak) {
                    return back()->withErrors(['stok' => 'Gagal. Stok fisik (' . $barang->stok_tersedia . ') lebih sedikit dari jumlah yang dilaporkan rusak.']);
                }
                $barang->decrement('stok_tersedia', $laporan->jumlah_rusak);
                $barang->increment('stok_diperbaiki', $laporan->jumlah_rusak);
            }
            
            // Transisi 2: Teknisi berhasil memperbaiki barang
            elseif ($statusLama === 'sedang_diperbaiki' && $statusBaru === 'selesai') {
                $barang->decrement('stok_diperbaiki', $laporan->jumlah_rusak);
                $barang->increment('stok_tersedia', $laporan->jumlah_rusak);
            }
            
            // Transisi 3: Teknisi memvonis barang rusak total (Afkir)
            elseif ($statusLama === 'sedang_diperbaiki' && $statusBaru === 'rusak_total') {
                $barang->decrement('stok_diperbaiki', $laporan->jumlah_rusak);
                $barang->decrement('total_stok', $laporan->jumlah_rusak);
            }

            $laporan->update([
                'status'          => $statusBaru,
                'catatan_petugas' => $request->catatan_petugas ?? $laporan->catatan_petugas
            ]);

            DB::commit();
            return back()->with('success', 'Status laporan dan stok barang berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['Terjadi kesalahan sistem saat menghitung stok: ' . $e->getMessage()]);
        }
    }
}