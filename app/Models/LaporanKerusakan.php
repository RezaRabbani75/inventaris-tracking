<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanKerusakan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kerusakans';

    protected $fillable = [
        'user_id',
        'barang_id',
        'peminjaman_id',
        'jumlah_rusak',
        'deskripsi_kerusakan',
        'status',
        'catatan_petugas',
    ];

    // Relasi ke Pelapor (user)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Barang
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    // Relasi ke data Peminjaman asal
    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }
}