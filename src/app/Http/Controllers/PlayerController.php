<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{
    public function store(Request $request)
    {
        // 選手データを保存する処理
        Player::create([
            'team_id' => $request->input('team_id'), // チームID
            'player_number' => $request->input('player_number'), // 背番号
            'foot' => $request->input('foot'), // 利き足
            'goals' => $request->input('goals'), // ゴール数
            'feature' => $request->input('feature'), // 特徴
        ]);

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
