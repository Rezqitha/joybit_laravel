<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    /**
     * API: Get all orders.
     */
    public function index(): JsonResponse
    {
        $orders = Pesanan::orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ]);
    }

    /**
     * API: Create new order.
     */
    public function store(Request $request): JsonResponse
    {
        $nama = $request->input('nama_pembeli');
        $total = $request->input('total_harga');

        if (!empty($nama) && !empty($total)) {
            Pesanan::create([
                'nama_pembeli' => $nama,
                'total_harga' => $total,
                'status' => 'Menunggu',
                'tanggal' => now()->toDateString(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan berhasil ditambahkan',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data tidak lengkap',
        ]);
    }

    /**
     * API: Update order status.
     */
    public function update(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $status = $request->input('status');

        if (!empty($id) && !empty($status)) {
            $updated = Pesanan::where('id', $id)->update(['status' => $status]);

            if ($updated) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Status pesanan diperbarui',
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui pesanan',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'ID atau status kosong',
        ]);
    }

    /**
     * API: Delete order.
     */
    public function destroy(Request $request, $id = null): JsonResponse
    {
        $delId = $id ?? $request->query('id') ?? $request->input('id');

        if (!empty($delId)) {
            $deleted = Pesanan::where('id', $delId)->delete();

            if ($deleted) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Pesanan berhasil dihapus',
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus pesanan',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'ID diperlukan',
        ]);
    }
}
