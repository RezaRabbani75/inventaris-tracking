<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Notifications\GeneralNotification;
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

        $peminjaman = Peminjaman::with('user')->findOrFail($id);
        $barang = Barang::findOrFail($peminjaman->barang_id);

        $statusLama = $peminjaman->status;
        $statusBaru = $request->status;

        // Validasi dan kurangi stok jika disetujui / dipinjam
        if (in_array($statusBaru, ['disetujui', 'dipinjam']) && $statusLama === 'menunggu') {
            if ($barang->stok_tersedia < $peminjaman->jumlah) {
                return back()->withErrors(['stok' => 'Gagal menyetujui. Stok perangkat tersisa (' . $barang->stok_tersedia . ') tidak mencukupi permintaan (' . $peminjaman->jumlah . ').']);
            }
            $barang->decrement('stok_tersedia', $peminjaman->jumlah);
        }

        // Kembalikan stok jika ditolak atau dikembalikan
        if (in_array($statusBaru, ['dikembalikan', 'ditolak']) && in_array($statusLama, ['disetujui', 'dipinjam'])) {
            $barang->increment('stok_tersedia', $peminjaman->jumlah);
        }

        // Update data transaksi
        $peminjaman->update([
            'status'                 => $statusBaru,
            'pesan_admin'            => $request->pesan_admin,
            'tanggal_kembali_aktual' => ($statusBaru === 'dikembalikan') ? now() : $peminjaman->tanggal_kembali_aktual,
        ]);

        // ==========================================
        // LOGIKA NOTIFIKASI OTOMATIS KE PEMINJAM
        // ==========================================
        $judulNotif = 'Status Peminjaman ' . ucfirst($statusBaru);
        $tipeNotif = 'info';
        $pesanNotif = 'Pengajuan peminjaman barang ' . $barang->nama_barang . ' Anda sekarang berstatus: ' . ucfirst($statusBaru) . '.';

        // Sesuaikan warna badge alert (type) berdasarkan status baru
        if ($statusBaru === 'disetujui' || $statusBaru === 'dikembalikan') {
            $tipeNotif = 'success';
        } elseif ($statusBaru === 'ditolak') {
            $tipeNotif = 'danger';
        } elseif ($statusBaru === 'dipinjam') {
            $tipeNotif = 'warning';
        }

        // Tambahkan info catatan admin ke dalam pesan jika diisi
        if ($request->pesan_admin) {
            $pesanNotif .= ' Catatan Admin: "' . $request->pesan_admin . '"';
        }

        // Kirimkan ke user yang meminjam barang tersebut
        if ($peminjaman->user) {
            $peminjaman->user->notify(new GeneralNotification(
                $judulNotif,
                $pesanNotif,
                url('peminjaman-saya'), // Diarahkan ke halaman histori milik user
                'Lihat Detail',
                $tipeNotif
            ));
        }
        // ==========================================

        return redirect()->route('persetujuan-peminjaman.index')
                         ->with('success', 'Status pengajuan berhasil diperbarui menjadi: ' . ucfirst($statusBaru));
    }
}