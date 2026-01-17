<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('karyawan_id')
                ->constrained('karyawan')
                ->cascadeOnDelete();

            $table->foreignId('toy_id')
                ->constrained('toys')
                ->cascadeOnDelete();

            $table->string('nama_customer');
            $table->integer('total_harga')->default(0);

            $table->dateTime('jam_mulai');
            $table->integer('durasi_menit');
            $table->dateTime('jam_selesai');

            $table->enum('status', ['aktif', 'selesai'])
                ->default('aktif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};