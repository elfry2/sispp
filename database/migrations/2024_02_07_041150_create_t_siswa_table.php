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
        Schema::create('t_siswa', function (Blueprint $table) {
            // $table->id();
            $table->string('nis')->unique();
            $table->primary('nis');
            $table->string('nama_siswa', 30);
            $table->string('alamat', 40);
            $table->date('tgl_lahir');
            $table->string('tempat_lahir', 30);
            $table->string('jk', 15);
            $table->string('nama_orang_tua', 15);
            $table->string('no_hp');
            $table->unsignedBigInteger('kd_kls');
            $table->foreign('kd_kls')->references('kd_kls')->on('t_kelas');
            $table->decimal('spp_perbulan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_siswa');
    }
};
