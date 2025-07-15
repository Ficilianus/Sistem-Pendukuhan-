<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up()
    {
    Schema::create('dokumen_penduduks', function (Blueprint $table) {
    $table->id();
    $table->string('nama_kepala_keluarga');
    $table->string('nama');
    $table->string('rt');
    $table->string('jenis_dokumen');
    $table->string('gender')->nullable();
    $table->date('tanggal_lahir')->nullable();
    $table->string('status_keluarga')->nullable();
    $table->string('nama_file');
    $table->timestamps();
});


}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_penduduks');
    }
};
