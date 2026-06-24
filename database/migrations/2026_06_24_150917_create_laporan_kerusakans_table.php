<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_kerusakans', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            
            $table->foreignId('peminjaman_id')->nullable()->constrained('peminjamans')->onDelete('set null');
            
            $table->integer('jumlah_rusak');
            $table->text('deskripsi_kerusakan');
            
            $table->enum('status', [
                'menunggu_tinjauan', 
                'sedang_diperbaiki',
                'selesai',          
                'rusak_total'      
            ])->default('menunggu_tinjauan');
            
            $table->text('catatan_petugas')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kerusakans');
    }
};