<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; //追加
use Illuminate\Support\Facades\Auth;

class CreateController extends Controller
{
    public function index()
    {        
        return view('create');
    }

    public function create()
    {
        
    }

    public function saveGameData(Request $request)
    {
        $data = file_get_contents('php://input');
        //dd($data);
        /*$data_unpacked = unpack('C*', $data);
        define("TESTFILE","../../storage/app/public/game_data/1.json");
        file_put_contents(\Storage::get(),$data);
        var_dump(file_get_contents(TESTFILE));*/
        \Storage::put('/public/game_data/'.Auth::id().'.json', $data);
    }
}
