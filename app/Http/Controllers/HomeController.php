<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth; //追加
use \App\Product;
use \App\Player;
use \App\History;
use Gate;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::where('owner_id', Auth::id())->get();   
        /*$color_palette = array('royalblue'=>array('r'=>65, 'g'=>105, 'b'=>225), 'teal'=>array('r'=>0, 'g'=>128, 'b'=>), 'mediumseagreen'=>array('r'=>60, 'g'=>179, 'b'=>113), 'darkorange'=>array('r'=>255, 'g'=>140, 'b'=>0), );
        $color_key = array_rand( $color_palette, 1);
        $color = $color_palette[$color_key];
        //指定した大きさの黒画像を生成
        header("Content-type: image/jpeg");
        $img = imagecreatetruecolor(100, 100);
        
        //画像背景の設定
        //背景色の指定
        $background_color = imagecolorallocate($img, 0, 0, 0);
        //画像を背景色で塗る
        imagefilledrectangle($img, 0, 0, 300, 300, $background_color);
        
        //画像に書き込むテキストの設定
        //テキスト色の指定
        $text_color = imagecolorallocate($img, 255, 255, 255);
        //画像に文字列を書き込む
        imagestring($img, 5, 5, 5,  'TESｈぼｈ', $text_color);
        
        //画像の出力
        imagejpeg($img);
        
        //画像の保存
        imagejpeg($img, storage_path('app/public/face_list/init_face/black.jpg'));
        
        //画像の消去（メモリの解放）
        imagedestroy($img);     */
        return view('home')->with(['products' => $products]);
    }

    public function sell(Request $request, $id) //merchant
    {
        Product::find($id)->fill([
            'price' => $request->price,
            'place' => 'market',
        ])->save();

        session()->flash('flash_success_message', 'sold');
        return redirect('/home');
    }

    public function cancel($id) //merchant
    {
        $product = Product::find($id); 
        if($product->place == 'market'){
            $product->fill([
                'place' => 'merchant',
            ])->save();

            session()->flash('flash_success_message', __('canceled'));
        }
        else{
            session()->flash('flash_danger_message', __('Already sold'));
        }
        return redirect('/home');
    }

    public function changeTax(Request $request) //government
    {
        Auth::user()->player->fill([
            'tax' => $request->tax,
        ])->save();

        History::create([
            'player_id' => Auth::id(),
            'log' => "Changed Tc ({$request->tax}%)",
        ]);

        session()->flash('toast_success', 'Success');
        return redirect('/home');
    }
}
