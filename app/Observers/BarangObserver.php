<?php

namespace App\Observers;

use App\Models\Barang;
use App\Models\ActivityLog;

class BarangObserver
{
    /**
     * Handle the Barang "created" event.
     */
    public function created(Barang $barang): void
    {
        ActivityLog::log(
            description: "Menambahkan perangkat baru: {$barang->nama_barang}",
            subject: 'Kelola Barang',
            event: 'created',
            model: $barang
        );
    }

    /**
     * Handle the Barang "updated" event.
     */
    public function updated(Barang $barang): void
    {
        $changes = $barang->getChanges();
        
        ActivityLog::log(
            description: "Memperbarui data perangkat: {$barang->nama_barang}",
            subject: 'Kelola Barang',
            event: 'updated',
            model: $barang,
            properties: ['perubahan' => $changes] 
        );
    }

    /**
     * Handle the Barang "deleted" event.
     */
    public function deleted(Barang $barang): void
    {
        ActivityLog::log(
            description: "Menghapus perangkat: {$barang->nama_barang}",
            subject: 'Kelola Barang',
            event: 'deleted',
            model: $barang
        );
    }
}