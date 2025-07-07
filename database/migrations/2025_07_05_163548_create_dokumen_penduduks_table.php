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
        $table->string('nama');
        $table->string('rt');
        $table->string('jenis_dokumen');
        $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable(); // hanya jika KTP
        $table->date('tanggal_lahir')->nullable(); // hanya jika KTP
        $table->string('nama_file'); // simpan nama file-nya
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
