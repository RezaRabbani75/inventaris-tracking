<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\LaporanKerusakan;

class HomeController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $data = [
            'total_barang' => 0,
            'sedang_dipinjam' => 0,
            'sedang_diperbaiki' => 0,
            'pinjaman_aktif' => 0,
            'laporan_dibuat' => 0,
            'tugas_perbaikan' => 0,
            'selesai_hari_ini' => 0,
        ];

        if ($user->hasRole('superadmin')) {
            $data['total_barang'] = Barang::sum('total_stok');
            $data['sedang_dipinjam'] = Peminjaman::whereIn('status', ['disetujui', 'dipinjam'])->sum('jumlah');
            $data['sedang_diperbaiki'] = Barang::sum('stok_diperbaiki');
            $data['perangkat_tersedia'] = $data['total_barang'] - ($data['sedang_dipinjam'] + $data['sedang_diperbaiki']);
            $data['perangkat_afkir']    = LaporanKerusakan::where('status', 'rusak_total')->sum('jumlah_rusak');
        } 
        elseif ($user->hasAnyRole(['teacher', 'student'])) {
            $data['pinjaman_aktif'] = Peminjaman::where('user_id', Auth::id())
                ->whereIn('status', ['menunggu', 'disetujui', 'dipinjam'])
                ->count();
            $data['laporan_dibuat'] = LaporanKerusakan::where('user_id', Auth::id())->count();
        } 
        elseif ($user->hasRole('technician')) {
            $data['tugas_perbaikan'] = LaporanKerusakan::where('status', 'sedang_diperbaiki')->count();
            $data['selesai_hari_ini'] = LaporanKerusakan::where('status', 'selesai')
                ->whereDate('updated_at', now()->toDateString())
                ->count();
        }

        return view('home', compact('data'));
    }

    public function blank()
    {
        return view('layouts.blank-page');
    }
}