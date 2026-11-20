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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id_user');
                $table->string('username', 255);
                $table->string('nama_lengkap', 255);
                $table->string('password', 255);
                $table->string('role', 20);
            });
        }

        if (!Schema::hasTable('produk')) {
            Schema::create('produk', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nama_produk', 255);
                $table->text('deskripsi')->nullable();
                $table->integer('harga')->default(0);
                $table->decimal('rating', 2, 1)->default(5.0);
                $table->integer('jumlah_ulasan')->default(0);
                $table->string('kategori', 50)->default('Dessert');
                $table->string('gambar', 255)->default('');
                $table->integer('stok')->default(0);
            });
        }

        if (!Schema::hasTable('produk_dessert')) {
            Schema::create('produk_dessert', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nama_dessert', 255);
                $table->integer('harga')->default(0);
                $table->integer('stok')->default(0);
                $table->text('deskripsi')->nullable();
            });
        }

        if (!Schema::hasTable('keranjang')) {
            Schema::create('keranjang', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nama_dessert', 255);
                $table->integer('harga')->default(0);
                $table->integer('jumlah')->default(1);
            });
        }

        if (!Schema::hasTable('validasi_prdouk')) {
            Schema::create('validasi_prdouk', function (Blueprint $table) {
                $table->increments('id_validasi');
                $table->string('nama_produk', 255);
                $table->string('nama_toko', 255);
                $table->text('status');
                $table->text('deskripsi')->nullable();
                $table->date('dibuat_pada');
            });
        }

        if (!Schema::hasTable('pesanan')) {
            Schema::create('pesanan', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nama_pembeli', 255);
                $table->integer('total_harga');
                $table->string('status', 50)->default('Menunggu');
                $table->date('tanggal');
            });
        }

        if (!Schema::hasTable('ulasan_produk')) {
            Schema::create('ulasan_produk', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nama_pembeli', 255);
                $table->integer('id_produk');
                $table->text('komentar');
                $table->integer('rating')->default(5);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan_produk');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('validasi_prdouk');
        Schema::dropIfExists('keranjang');
        Schema::dropIfExists('produk_dessert');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('users');
    }
};
