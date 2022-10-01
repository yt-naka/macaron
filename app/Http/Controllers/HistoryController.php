<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth; //追加
use \App\History;

class HistoryController extends Controller
{
    public function index(){
        $histories = History::where('player_id', Auth::id())->latest()->get();
        return view('history')->with(['histories' => $histories]);
        
    }
}
