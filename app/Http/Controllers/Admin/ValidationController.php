<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ValidasiProduk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ValidationController extends Controller
{
    /**
     * Monolithic Web: List products to validate.
     */
    public function index(): View
    {
        $products = ValidasiProduk::orderBy('id_validasi', 'desc')->get();
        return view('admin.validasi', compact('products'));
    }

    /**
     * Monolithic Web: Add product to validate.
     */
    public function store(Request $request): RedirectResponse
    {
        ValidasiProduk::create([
            'nama_produk' => $request->input('nama_produk'),
            'nama_toko' => $request->input('nama_toko'),
            'status' => 'Menunggu Validasi',
            'deskripsi' => $request->input('deskripsi', ''),
            'dibuat_pada' => now()->toDateString(),
        ]);

        return redirect()->back();
    }

    /**
     * Monolithic Web: Delete validation product.
     */
    public function destroy(Request $request, $id = null): RedirectResponse
    {
        $deleteId = $id ?? $request->query('delete_id');
        if ($deleteId) {
            ValidasiProduk::where('id_validasi', $deleteId)->delete();
        }
        return redirect()->back();
    }

    /**
     * API: Get all validation products.
     */
    public function apiGet(): JsonResponse
    {
        $products = ValidasiProduk::orderBy('id_validasi', 'desc')->get();
        return response()->json($products);
    }

    /**
     * API: Create validation product.
     */
    public function apiStore(Request $request): JsonResponse
    {
        $item = ValidasiProduk::create([
            'nama_produk' => $request->input('nama_produk'),
            'nama_toko' => $request->input('nama_toko'),
            'status' => 'Menunggu Validasi',
            'deskripsi' => $request->input('deskripsi', ''),
            'dibuat_pada' => now()->toDateString(),
        ]);

        return response()->json([
            'status' => 'success',
            'id' => $item->id_validasi,
        ]);
    }

    /**
     * API: Update status.
     */
    public function apiUpdate(Request $request): JsonResponse
    {
        $id = $request->input('id_validasi');
        $status = $request->input('status');

        $updated = ValidasiProduk::where('id_validasi', $id)->update([
            'status' => $status,
        ]);

        if ($updated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Status updated',
            ]);
        }

        return response()->json(['status' => 'error']);
    }

    /**
     * API: Delete validation product.
     */
    public function apiDestroy(Request $request, $id = null): JsonResponse
    {
        $deleteId = $id ?? $request->query('id') ?? $request->input('id_validasi');

        if (!$deleteId) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID required',
            ]);
        }

        $deleted = ValidasiProduk::where('id_validasi', $deleteId)->delete();

        if ($deleted) {
            return response()->json(['status' => 'deleted']);
        }

        return response()->json(['status' => 'error']);
    }
}
