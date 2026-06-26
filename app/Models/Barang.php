<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'foto',
        'deskripsi',
        'total_stok',
        'stok_tersedia',
        'stok_dipinjam',
        'stok_rusak',
        'stok_diperbaiki',
    ];

    protected function casts(): array
    {
        return [
            'total_stok' => 'integer',
            'stok_tersedia' => 'integer',
            'stok_dipinjam' => 'integer',
            'stok_rusak' => 'integer',
            'stok_diperbaiki' => 'integer',
        ];
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function laporanKerusakans()
    {
        return $this->hasMany(LaporanKerusakan::class);
    }
}