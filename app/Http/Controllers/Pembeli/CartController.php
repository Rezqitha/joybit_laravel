<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display cart page.
     */
    public function index(): View
    {
        return view('pembeli.keranjang');
    }

    /**
     * API: Get cart items.
     */
    public function apiGet(): JsonResponse
    {
        $items = DB::table('keranjang as k')
            ->leftJoin('produk as p', 'k.nama_dessert', '=', 'p.nama_produk')
            ->select('k.*', 'p.gambar')
            ->get();

        $data = [];
        foreach ($items as $item) {
            $row = (array) $item;
            $row['subtotal'] = (int)$row['harga'] * (int)$row['jumlah'];
            $data[] = $row;
        }

        return response()->json($data);
    }

    /**
     * API: Add item to cart.
     */
    public function apiStore(Request $request): JsonResponse
    {
        $nama = $request->input('nama_dessert');
        $harga = $request->input('harga');
        $jumlah = $request->input('jumlah', 1);

        $existing = Keranjang::where('nama_dessert', $nama)->first();

        if ($existing) {
            $existing->increment('jumlah', (int)$jumlah);
            $msg = 'Jumlah dessert berhasil ditambah';
        } else {
            Keranjang::create([
                'nama_dessert' => $nama,
                'harga' => $harga,
                'jumlah' => $jumlah,
            ]);
            $msg = 'Dessert baru berhasil dimasukkan ke keranjang';
        }

        return response()->json(['message' => $msg]);
    }

    /**
     * API: Update item quantity in cart.
     */
    public function apiUpdate(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $jumlah = $request->input('jumlah');

        $updated = Keranjang::where('id', $id)->update(['jumlah' => $jumlah]);

        if ($updated) {
            return response()->json(['message' => 'Jumlah berhasil diperbarui']);
        }

        return response()->json(['message' => 'Update GAGAL']);
    }

    /**
     * API: Delete item from cart.
     */
    public function apiDestroy(Request $request, $id = null): JsonResponse
    {
        $delId = $id ?? $request->query('id') ?? $request->input('id');

        $deleted = Keranjang::where('id', $delId)->delete();

        if ($deleted) {
            return response()->json(['message' => 'Item berhasil dihapus']);
        }

        return response()->json(['message' => 'Gagal menghapus item']);
    }
}
