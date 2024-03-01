<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{
    public function store(Request $request)
    {
        // リクエストから選手情報を取得します
        $playerNumber = $request->input('playerNumber');
        $foot = $request->input('foot');
        $goals = $request->input('goals');
        $feature = $request->input('feature');

        // Playerモデルのインスタンスを作成し、選手情報をセットします
        $player = new Player();
        $player->player_number = $playerNumber;
        $player->foot = $foot;
        $player->goals = $goals;
        $player->feature = $feature;

        // データベースに保存します
        $player->save();

        // 適切なレスポンスを返します（例: データが保存された後、リダイレクトする）
        return redirect()->back()->with('success', '選手情報が保存されました。');
    }
}
