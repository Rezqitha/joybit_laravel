<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\ProdukDessert;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed initial users if table is empty
        if (User::count() === 0) {
            User::create(['id_user' => 1, 'username' => 'rere', 'nama_lengkap' => 'reruy', 'password' => 'rere123', 'role' => 'pembeli']);
            User::create(['id_user' => 2, 'username' => 'andita', 'nama_lengkap' => 'andituy', 'password' => 'andita123', 'role' => 'admin']);
            User::create(['id_user' => 3, 'username' => 'lais', 'nama_lengkap' => 'laisuy', 'password' => 'lais123', 'role' => 'penjual']);
        }

        // Seed initial produk
        if (Produk::count() === 0) {
            Produk::create([
                'id' => 1,
                'nama_produk' => 'Strawberry Cheesecake',
                'deskripsi' => 'Strawberry Cheesecake yang lembut dan lezat akan membuat harimu lebih sempurna. segera penuhi craving mu!',
                'harga' => 45000,
                'rating' => 4.5,
                'jumlah_ulasan' => 7,
                'kategori' => 'Dessert',
                'gambar' => 'strawberrycheesecake.jpg',
                'stok' => 15,
            ]);
        }

        // Seed initial produk_dessert
        if (ProdukDessert::count() === 0) {
            ProdukDessert::create(['nama_dessert' => 'Dubai Chewy Cookie', 'harga' => 20000, 'stok' => 10, 'deskripsi' => '']);
            ProdukDessert::create(['nama_dessert' => 'Strawberry Shortcake', 'harga' => 35000, 'stok' => 5, 'deskripsi' => '']);
            ProdukDessert::create(['nama_dessert' => 'Fudgy Brownies', 'harga' => 25000, 'stok' => 8, 'deskripsi' => '']);
        }
    }
}
