<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth; //追加

class AnalysisController extends Controller
{
    public function index()
    {
        return view('analysis');
    }

    public function moneyHistroy()
    {
        $url = storage_path().'/app/public/chart_datas/'.Auth::id().'.json';
        $json = file_get_contents($url);
        echo $json;
    }
}
