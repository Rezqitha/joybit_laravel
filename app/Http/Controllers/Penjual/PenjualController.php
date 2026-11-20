<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PenjualController extends Controller
{
    /**
     * Display the seller dashboard.
     */
    public function index(): View
    {
        return view('penjual.index');
    }
}
