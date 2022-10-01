<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Player; //追加

class RankingController extends Controller
{
    public function index(){
        $top_players = Player::where('role', 'merchant')->orderBy('money', 'desc')->take(100)->get();
        return view('ranking')->with(['top_players' => $top_players]);
    }
}
