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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            
            $table->integer('jumlah');
            $table->string('tujuan_pinjam');
            
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->dateTime('tanggal_kembali_aktual')->nullable();
            
            $table->enum('status', [
                'menunggu',     
                'disetujui',    
                'ditolak',      
                'dipinjam',     
                'dikembalikan'  
            ])->default('menunggu');
            
            $table->text('pesan_admin')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};