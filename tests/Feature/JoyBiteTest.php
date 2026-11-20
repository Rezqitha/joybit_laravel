<?php

namespace Tests\Feature;

use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\UlasanProduk;
use App\Models\User;
use App\Models\ValidasiProduk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JoyBiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic user
        User::create([
            'id_user' => 1,
            'username' => 'rere',
            'nama_lengkap' => 'reruy',
            'password' => 'rere123',
            'role' => 'pembeli',
        ]);
        User::create([
            'id_user' => 2,
            'username' => 'andita',
            'nama_lengkap' => 'andituy',
            'password' => 'andita123',
            'role' => 'admin',
        ]);
        User::create([
            'id_user' => 3,
            'username' => 'lais',
            'nama_lengkap' => 'laisuy',
            'password' => 'lais123',
            'role' => 'penjual',
        ]);
    }

    public function test_root_redirects_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_login_page_renders(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $responseHtml = $this->get('/login.html');
        $responseHtml->assertStatus(200);
    }

    public function test_login_api_functionality(): void
    {
        // Missing fields
        $res = $this->postJson('/login.php', ['username' => '']);
        $res->assertJson([
            'status' => 'error',
            'message' => 'Username dan password harus diisi!',
        ]);

        // Wrong password
        $res = $this->postJson('/login.php', ['username' => 'rere', 'password' => 'salah']);
        $res->assertJson([
            'status' => 'error',
            'message' => 'Username atau password salah!',
        ]);

        // Success
        $res = $this->postJson('/login.php', ['username' => 'rere', 'password' => 'rere123']);
        $res->assertJson([
            'status' => 'success',
            'message' => 'Login berhasil!',
            'data' => [
                'id_user' => 1,
                'username' => 'rere',
                'role' => 'pembeli',
            ],
        ]);
    }

    public function test_admin_monolithic_crud_users(): void
    {
        // View
        $response = $this->get('/admin/user_admin.php');
        $response->assertStatus(200);
        $response->assertSee('Manajemen User');

        // Create
        $postRes = $this->post('/admin/user_admin.php', [
            'username' => 'budi',
            'nama_lengkap' => 'Budi Santoso',
            'password' => 'budi123',
            'role' => 'pembeli',
        ]);
        $this->assertDatabaseHas('users', ['username' => 'budi']);

        $user = User::where('username', 'budi')->first();

        // Update
        $this->post('/admin/user_admin.php', [
            'edit_id' => $user->id_user,
            'username' => 'budi_updated',
            'nama_lengkap' => 'Budi Santoso Edit',
            'password' => 'budi123',
            'role' => 'pembeli',
        ]);
        $this->assertDatabaseHas('users', ['username' => 'budi_updated']);

        // Delete
        $this->get('/admin/user_admin.php?delete_id=' . $user->id_user);
        $this->assertDatabaseMissing('users', ['id_user' => $user->id_user]);
    }

    public function test_admin_monolithic_crud_validasi(): void
    {
        // View
        $response = $this->get('/admin/validasi.php');
        $response->assertStatus(200);

        // Add
        $this->post('/admin/validasi.php', [
            'nama_produk' => 'Cheesecake Lumer',
            'nama_toko' => 'Toko Enak',
            'deskripsi' => 'Enak sekali',
        ]);
        $this->assertDatabaseHas('validasi_prdouk', ['nama_produk' => 'Cheesecake Lumer']);

        $item = ValidasiProduk::where('nama_produk', 'Cheesecake Lumer')->first();

        // Delete
        $this->get('/admin/validasi.php?delete_id=' . $item->id_validasi);
        $this->assertDatabaseMissing('validasi_prdouk', ['id_validasi' => $item->id_validasi]);
    }

    public function test_api_key_middleware_protection(): void
    {
        // No API key
        $res = $this->getJson('/penjual/dessert_api.php');
        $res->assertStatus(401);
        $res->assertJson(['status' => 'error', 'message' => 'Invalid API Key']);

        // Wrong API key
        $res = $this->getJson('/penjual/dessert_api.php?api_key=wrong');
        $res->assertStatus(401);

        // Valid API key
        $res = $this->getJson('/penjual/dessert_api.php?api_key=joybite123');
        $res->assertStatus(200);
    }

    public function test_penjual_dessert_crud_api(): void
    {
        // Create
        $res = $this->postJson('/penjual/dessert_api.php?api_key=joybite123', [
            'nama_dessert' => 'Choco Lava',
            'harga' => 30000,
            'stok' => 20,
        ]);
        $res->assertJson(['status' => 'success', 'message' => 'dessert berhasil ditambahkan']);

        $dessert = Produk::where('nama_produk', 'Choco Lava')->first();
        $this->assertNotNull($dessert);

        // Read
        $getRes = $this->getJson('/penjual/dessert_api.php?api_key=joybite123');
        $getRes->assertJson(['status' => 'success']);

        // Update
        $putRes = $this->putJson('/penjual/dessert_api.php?api_key=joybite123', [
            'id' => $dessert->id,
            'nama_dessert' => 'Choco Lava Premium',
            'harga' => 35000,
            'stok' => 15,
        ]);
        $putRes->assertJson(['status' => 'success', 'message' => 'Data dessert berhasil diperbarui!']);

        // Delete
        $delRes = $this->deleteJson('/penjual/dessert_api.php?id=' . $dessert->id . '&api_key=joybite123');
        $delRes->assertJson(['status' => 'success', 'message' => 'Dessert berhasil dihapus!']);
    }

    public function test_penjual_pesanan_crud_api(): void
    {
        // Create order
        $res = $this->postJson('/penjual/pesanan_api.php?api_key=joybite123', [
            'nama_pembeli' => 'rere',
            'total_harga' => 50000,
        ]);
        $res->assertJson(['status' => 'success', 'message' => 'Pesanan berhasil ditambahkan']);

        $order = Pesanan::first();
        $this->assertNotNull($order);

        // Read orders
        $getRes = $this->getJson('/penjual/pesanan_api.php?api_key=joybite123');
        $getRes->assertJson(['status' => 'success']);

        // Update order status
        $putRes = $this->putJson('/penjual/pesanan_api.php?api_key=joybite123', [
            'id' => $order->id,
            'status' => 'Selesai',
        ]);
        $putRes->assertJson(['status' => 'success', 'message' => 'Status pesanan diperbarui']);

        // Delete order
        $delRes = $this->deleteJson('/penjual/pesanan_api.php?id=' . $order->id . '&api_key=joybite123');
        $delRes->assertJson(['status' => 'success', 'message' => 'Pesanan berhasil dihapus']);
    }

    public function test_pembeli_cart_api(): void
    {
        // Add item
        $res = $this->postJson('/pembeli/cart_api.php?api_key=joybite123', [
            'nama_dessert' => 'Pudding Mangga',
            'harga' => 15000,
            'jumlah' => 2,
        ]);
        $res->assertJson(['message' => 'Dessert baru berhasil dimasukkan ke keranjang']);

        $item = Keranjang::where('nama_dessert', 'Pudding Mangga')->first();
        $this->assertNotNull($item);

        // Get cart
        $getRes = $this->getJson('/pembeli/cart_api.php?api_key=joybite123');
        $getRes->assertStatus(200);

        // Update qty
        $putRes = $this->putJson('/pembeli/cart_api.php?api_key=joybite123', [
            'id' => $item->id,
            'jumlah' => 4,
        ]);
        $putRes->assertJson(['message' => 'Jumlah berhasil diperbarui']);

        // Delete item
        $delRes = $this->deleteJson('/pembeli/cart_api.php?id=' . $item->id . '&api_key=joybite123');
        $delRes->assertJson(['message' => 'Item berhasil dihapus']);
    }

    public function test_pembeli_review_api(): void
    {
        // Add review
        $res = $this->postJson('/pembeli/ulasan_api.php?api_key=joybite123', [
            'nama_pembeli' => 'rere',
            'id_produk' => 1,
            'komentar' => 'Rasanya sangat lezat!',
            'rating' => 5,
        ]);
        $res->assertJson(['status' => 'success', 'message' => 'Ulasan berhasil dikirim']);

        $review = UlasanProduk::first();
        $this->assertNotNull($review);

        // Get reviews
        $getRes = $this->getJson('/pembeli/ulasan_api.php?api_key=joybite123');
        $getRes->assertJson(['status' => 'success']);

        // Edit review
        $putRes = $this->putJson('/pembeli/ulasan_api.php?api_key=joybite123', [
            'id' => $review->id,
            'komentar' => 'Sangat lezat dan manis!',
            'rating' => 4,
        ]);
        $putRes->assertJson(['status' => 'success', 'message' => 'Ulasan berhasil diperbarui']);

        // Delete review
        $delRes = $this->deleteJson('/pembeli/ulasan_api.php?id=' . $review->id . '&api_key=joybite123');
        $delRes->assertJson(['status' => 'success', 'message' => 'Ulasan berhasil dihapus']);
    }
}
