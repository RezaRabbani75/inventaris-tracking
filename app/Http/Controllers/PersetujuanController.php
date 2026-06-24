<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Http\Request;

class PersetujuanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'barang'])->latest()->get();
        
        return view('persetujuan-peminjaman.index', compact('peminjamans'));
    }

    public function show(string $id)
    {
        $peminjaman = Peminjaman::with(['user', 'barang'])->findOrFail($id);
        
        return view('persetujuan-peminjaman.show', compact('peminjaman'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'status'      => 'required|in:menunggu,disetujui,ditolak,dipinjam,dikembalikan',
            'pesan_admin' => 'nullable|string'
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        $barang = Barang::findOrFail($peminjaman->barang_id);

        $statusLama = $peminjaman->status;
        $statusBaru = $request->status;

        if (in_array($statusBaru, ['disetujui', 'dipinjam']) && $statusLama === 'menunggu') {
            if ($barang->stok_tersedia < $peminjaman->jumlah) {
                return back()->withErrors(['stok' => 'Gagal menyetujui. Stok perangkat tersisa (' . $barang->stok_tersedia . ') tidak mencukupi permintaan (' . $peminjaman->jumlah . ').']);
            }
            $barang->decrement('stok_tersedia', $peminjaman->jumlah);
        }

        if (in_array($statusBaru, ['dikembalikan', 'ditolak']) && in_array($statusLama, ['disetujui', 'dipinjam'])) {
            $barang->increment('stok_tersedia', $peminjaman->jumlah);
        }

        $peminjaman->update([
            'status'                 => $statusBaru,
            'pesan_admin'            => $request->pesan_admin,
            'tanggal_kembali_aktual' => ($statusBaru === 'dikembalikan') ? now() : $peminjaman->tanggal_kembali_aktual,
        ]);

        return redirect()->route('persetujuan-peminjaman.index')
                         ->with('success', 'Status pengajuan berhasil diperbarui menjadi: ' . ucfirst($statusBaru));
    }
}
