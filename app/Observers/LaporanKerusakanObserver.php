<?php

namespace App\Observers;

use App\Models\LaporanKerusakan;
use App\Models\ActivityLog;

class LaporanKerusakanObserver
{
    /**
     * Handle the LaporanKerusakan "created" event.
     */
    public function created(LaporanKerusakan $laporan): void
    {
        $namaBarang = $laporan->barang->nama_barang ?? 'Perangkat (ID: '.$laporan->barang_id.')';
        
        ActivityLog::log(
            description: "Melaporkan kerusakan untuk: {$namaBarang}",
            subject: 'Laporan Kerusakan',
            event: 'created',
            model: $laporan
        );
    }

    /**
     * Handle the LaporanKerusakan "updated" event.
     */
    public function updated(LaporanKerusakan $laporan): void
    {
        $namaBarang = $laporan->barang->nama_barang ?? 'Perangkat (ID: '.$laporan->barang_id.')';
        $changes = $laporan->getChanges();

        if ($laporan->isDirty('status')) {
            ActivityLog::log(
                description: "Pembaruan proses perbaikan (Status: {$laporan->status}) untuk: {$namaBarang}",
                subject: 'Perbaikan Barang', 
                event: 'updated',
                model: $laporan,
                properties: ['perubahan' => $changes]
            );
        } else {
            ActivityLog::log(
                description: "Memperbarui data laporan kerusakan: {$namaBarang}",
                subject: 'Laporan Kerusakan',
                event: 'updated',
                model: $laporan,
                properties: ['perubahan' => $changes]
            );
        }
    }

    /**
     * Handle the LaporanKerusakan "deleted" event.
     */
    public function deleted(LaporanKerusakan $laporan): void
    {
        $namaBarang = $laporan->barang->nama_barang ?? 'Perangkat (ID: '.$laporan->barang_id.')';
        
        ActivityLog::log(
            description: "Menghapus laporan kerusakan: {$namaBarang}",
            subject: 'Laporan Kerusakan',
            event: 'deleted',
            model: $laporan
        );
    }
}