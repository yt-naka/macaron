<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index($symbol)
    {
        return view('stock')->with(['symbol' => $symbol]);
    }
}
