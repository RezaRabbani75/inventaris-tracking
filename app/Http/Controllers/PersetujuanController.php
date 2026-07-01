<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersetujuanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'barang'])->latest();

        $roleFilter = $request->input('peran');

        if ($roleFilter && in_array($roleFilter, ['teacher', 'student'])) {
            $query->whereHas('user.roles', function ($q) use ($roleFilter) {
                $q->where('name', $roleFilter);
            });
        }

        $peminjamans = $query->get();
        
        return view('persetujuan-peminjaman.index', compact('peminjamans', 'roleFilter'));
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

        return DB::transaction(function () use ($request, $id) {
            
            $peminjaman = Peminjaman::with('user')->lockForUpdate()->findOrFail($id);
            $barang = Barang::lockForUpdate()->findOrFail($peminjaman->barang_id);

            $statusLama = $peminjaman->status;
            $statusBaru = $request->status;

            if ($statusLama === $statusBaru) {
                return redirect()->route('persetujuan-peminjaman.index')
                                 ->with('info', 'Tidak ada perubahan status pada pengajuan tersebut.');
            }

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

            $judulNotif = 'Status Peminjaman ' . ucfirst($statusBaru);
            $tipeNotif = 'info';
            $pesanNotif = 'Pengajuan peminjaman barang ' . $barang->nama_barang . ' Anda sekarang berstatus: ' . ucfirst($statusBaru) . '.';

            if ($statusBaru === 'disetujui' || $statusBaru === 'dikembalikan') {
                $tipeNotif = 'success';
            } elseif ($statusBaru === 'ditolak') {
                $tipeNotif = 'danger';
            } elseif ($statusBaru === 'dipinjam') {
                $tipeNotif = 'warning';
            }

            if ($request->pesan_admin) {
                $pesanNotif .= ' Catatan Admin: "' . $request->pesan_admin . '"';
            }

            if ($peminjaman->user) {
                $peminjaman->user->notify(new GeneralNotification(
                    $judulNotif,
                    $pesanNotif,
                    url('peminjaman-saya'),
                    'Lihat Detail',
                    $tipeNotif
                ));
            }

            return redirect()->route('persetujuan-peminjaman.index')
                             ->with('success', 'Status pengajuan berhasil diperbarui menjadi: ' . ucfirst($statusBaru));
        });
    }
}