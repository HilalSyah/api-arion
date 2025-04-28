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
        Schema::create('membership_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('user');  // Relasi ke tabel users
            $table->foreignId('id_pembayaran')->constrained('payment');  // Relasi ke tabel payments
            $table->timestamp('tanggal_mulai');
            $table->timestamp('tanggal_berakhir');
            $table->string('jenis_membership');
            $table->decimal('harga', 15, 2);
            $table->string('kode_barcode')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_user');
    }
};
