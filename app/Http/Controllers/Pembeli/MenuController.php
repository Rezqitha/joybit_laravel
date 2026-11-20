<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Display buyer menu page.
     */
    public function index(): View
    {
        return view('pembeli.menu');
    }

    /**
     * API: Get or search products.
     */
    public function apiGetOrSearch(Request $request): JsonResponse
    {
        $keyword = $request->input('keyword');

        if (!empty($keyword)) {
            $products = Produk::where('nama_produk', 'like', "%{$keyword}%")
                ->orWhere('deskripsi', 'like', "%{$keyword}%")
                ->get();
        } else {
            $products = Produk::all();
        }

        return response()->json($products);
    }
}
