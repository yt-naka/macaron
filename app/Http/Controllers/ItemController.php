<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {        
        //$items = file_get_contents('storage/game_data/1.json');
        //dd($items);
        $items = \Storage::get('public/game_data/1.json');
        $items = json_decode($items);
        return view('item')->with(['items' => $items->item]);
    }

    public function getItemData()
    {

    }
}
