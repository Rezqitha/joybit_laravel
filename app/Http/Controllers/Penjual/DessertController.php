<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DessertController extends Controller
{
    /**
     * API: Get dessert list.
     */
    public function index(): JsonResponse
    {
        $desserts = Produk::select('id', 'nama_produk as nama_dessert', 'harga', 'stok')->get();

        if ($desserts->count() > 0) {
            return response()->json([
                'status' => 'success',
                'data' => $desserts,
            ]);
        }

        return response()->json([
            'status' => 'empty',
            'message' => 'dessert tidak ada',
        ]);
    }

    /**
     * API: Create new dessert.
     */
    public function store(Request $request): JsonResponse
    {
        $nama = $request->input('nama_dessert');
        $harga = $request->input('harga');
        $stok = $request->input('stok');

        if (!empty($nama) && !empty($harga) && !empty($stok)) {
            Produk::create([
                'nama_produk' => $nama,
                'harga' => $harga,
                'stok' => $stok,
                'deskripsi' => '',
                'kategori' => 'Dessert',
                'rating' => 5.0,
                'jumlah_ulasan' => 0,
                'gambar' => '',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'dessert berhasil ditambahkan',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'data tidak lengkap',
        ]);
    }

    /**
     * API: Update dessert.
     */
    public function update(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $nama = $request->input('nama_dessert');
        $harga = $request->input('harga');
        $stok = $request->input('stok');

        if (!empty($id) && !empty($nama) && !empty($harga) && !empty($stok)) {
            $updated = Produk::where('id', $id)->update([
                'nama_produk' => $nama,
                'harga' => $harga,
                'stok' => $stok,
            ]);

            if ($updated) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data dessert berhasil diperbarui!',
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data tidak lengkap!',
        ]);
    }

    /**
     * API: Delete dessert.
     */
    public function destroy(Request $request, $id = null): JsonResponse
    {
        $delId = $id ?? $request->query('id') ?? $request->input('id');

        if ($delId) {
            $deleted = Produk::where('id', $delId)->delete();
            if ($deleted) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Dessert berhasil dihapus!',
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'ID tidak ditemukan',
        ]);
    }
}
