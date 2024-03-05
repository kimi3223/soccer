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
        $teamId = $request->input('team_id');

        // Playerモデルのインスタンスを作成し、選手情報をセットします
        $player = new Player();
        $player->player_number = $playerNumber;
        $player->foot = $foot;
        $player->goals = $goals;
        $player->feature = $feature;
        $player->team_id = $teamId;

        // データベースに保存します
        $player->save();

        // 適切なレスポンスを返します（例: データが保存された後、リダイレクトする）
        return redirect()->back()->with('success', '選手情報が保存されました。');
    }

    public function save(Request $request)
    {
        // リクエストから選手のデータを取得
        $playerData = $request->only(['team_id', 'player_Number', 'foot', 'goals', 'feature']);

        // データベースに選手のデータを保存
        Player::create($playerData);

        // ホームページにリダイレクト
        return redirect('/')->with('success', 'データが保存されました。');
    }

    public function saveAllData(Request $request)
    {
        // チーム1のデータを保存
        $team1Formation = $request->input('team1Formation');
        $team1Players = $request->input('team1Players');

        // チーム1の選手情報を保存
        foreach ($team1Players as $playerData) {
            Player::create([
                'team_id' => $playerData['team_id'],
                'player_number' => $playerData['player_number'],
                'foot' => $playerData['foot'],
                'goals' => $playerData['goals'],
                'feature' => $playerData['feature']
            ]);
        }

        // チーム2のデータを保存
        $team2Formation = $request->input('team2Formation');
        $team2Players = $request->input('team2Players');

        // チーム2の選手情報を保存
        foreach ($team2Players as $playerData) {
            Player::create([
                'team_id' => $playerData['team_id'],
                'player_number' => $playerData['player_number'],
                'foot' => $playerData['foot'],
                'goals' => $playerData['goals'],
                'feature' => $playerData['feature']
            ]);
        }

        // マッチのデータを保存
        Match::create([
            'team1_id' => $team1Formation['team_id'],
            'team2_id' => $team2Formation['team_id'],
            'team1_formation' => $team1Formation['formation'],
            'team2_formation' => $team2Formation['formation'],
        ]);

        // ホームページにリダイレクト
        return redirect('/')->with('success', 'データが保存されました。');
    }
}
