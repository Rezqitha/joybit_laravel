<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\UlasanProduk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Display reviews page.
     */
    public function index(): View
    {
        return view('pembeli.ulasan');
    }

    /**
     * API: Get reviews (optionally by product id).
     */
    public function apiGet(Request $request): JsonResponse
    {
        $idProduk = $request->query('id_produk');

        if (!empty($idProduk)) {
            $reviews = UlasanProduk::where('id_produk', $idProduk)->orderBy('id', 'desc')->get();
        } else {
            $reviews = UlasanProduk::orderBy('id', 'desc')->get();
        }

        return response()->json([
            'status' => 'success',
            'data' => $reviews,
        ]);
    }

    /**
     * API: Add review.
     */
    public function apiStore(Request $request): JsonResponse
    {
        $nama = $request->input('nama_pembeli');
        $idProduk = $request->input('id_produk');
        $komentar = $request->input('komentar');
        $rating = $request->input('rating', 5);

        if (!empty($nama) && !empty($idProduk) && !empty($komentar)) {
            UlasanProduk::create([
                'nama_pembeli' => $nama,
                'id_produk' => $idProduk,
                'komentar' => $komentar,
                'rating' => $rating,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Ulasan berhasil dikirim',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data tidak lengkap',
        ]);
    }

    /**
     * API: Update review.
     */
    public function apiUpdate(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $komentar = $request->input('komentar');
        $rating = $request->input('rating');

        if (!empty($id) && !empty($komentar)) {
            $updateData = ['komentar' => $komentar];
            if (!empty($rating)) {
                $updateData['rating'] = $rating;
            }

            $updated = UlasanProduk::where('id', $id)->update($updateData);

            if ($updated) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Ulasan berhasil diperbarui',
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui ulasan',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'ID atau komentar kosong',
        ]);
    }

    /**
     * API: Delete review.
     */
    public function apiDestroy(Request $request, $id = null): JsonResponse
    {
        $delId = $id ?? $request->query('id') ?? $request->input('id');

        if (!empty($delId)) {
            $deleted = UlasanProduk::where('id', $delId)->delete();

            if ($deleted) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Ulasan berhasil dihapus',
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus ulasan',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'ID diperlukan',
        ]);
    }
}
