<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Player; //追加
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class GovernmentsController extends Controller
{
    public function index(Request $request)
    {
        if(is_null($request->order) || $request->order == 'money'){
            $governments = Player::where('role', 'government')->orderBy('money', 'desc')->get();
        }
        elseif($request->order == 'players'){
            $governments_id = DB::table('players')->where('role', 'government')->pluck('id')->toArray();
            $sql = DB::table('players')
            ->select('government_id', DB::raw('count(*) as have_players'))
            ->whereIn('government_id', $governments_id)
            ->groupBy('government_id');
            $governments = DB::table('players')
            ->select('players.id','players.role','players.name','players.money','players.tax','players.government_id','players.chain','players.created_at','players.updated_at','a.have_players')
            ->leftJoinSub($sql, 'a', function ($join) {
                $join->on('players.id', '=', 'a.government_id');
            })
            ->where('role', 'government')
            ->orderBy('have_players', 'desc')
            ->get();
        }
        
        return view('governments')->with(['governments' => $governments]);
    }

    public function select($id)
    {
        Auth::user()->player->fill([
            'government_id' => $id,
        ])->save();

        return redirect('/home');
    }
}
