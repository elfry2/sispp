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
        Schema::create('t_dtl_pembayaran', function (Blueprint $table) {
            $table->id('id_detail_pembayaran');
            $table->unsignedBigInteger('id_pembayaran');
            $table
                ->foreign('id_pembayaran')
                ->references('id_pembayaran')
                ->on('t_pembayaran');
            $table->integer('tahun_pembayaran');
            $table->integer('bulan');
            $table->decimal('jumlah', 9, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_dtl_pembayaran');
    }
};
