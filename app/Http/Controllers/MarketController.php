<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth; //追加
use \App\Player;
use \App\Product;
use \App\History;
use \App\User;
use Illuminate\Support\Facades\Storage;

class MarketController extends Controller
{
    public function index()
    {
        $products = Product::where('place', 'merket')->orderBy('price', 'desc')->get();
        $chains = Auth::user()->player->chain;
        if($chains){
            $chains = explode(',', $chains);
            $chains_info = User::find($chains);
        }else{
            $chains_info = 0;
        }
        
        return view('market')->with(['products' => $products, 'chains_info' => $chains_info]);
    }

    public function buy($id)
    {
        $items = Product::find($id);
        $buyer = Auth::user()->player;
        $seller = Player::find($product->owner_id);
        $government = Player::find($buyer->government_id);

        $product->fill([
            'owner_id' => $buyer->id,
            'place' => 'player',
        ])->save();
    
        $buyer->fill([
            'money' => $buyer->money - $product->price * (1 - $buyer->tax/100),
            'chain' => $buyer->chain ? $buyer->chain.",{$seller->id}" : $seller->id,
        ])->save();

        $seller->fill([
            'money' => $seller->money + $product->price,
            'chain' => $seller->chain ? $seller->chain.",{$buyer->id}" : $buyer->id,
        ])->save();
        
        $government->fill([
            'money' => $government->money - $product->price * $buyer->tax/100,
        ])->save();

        return redirect('/home');
    }
}
