<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Match;
use App\Models\Player;
use App\Models\Formation;
use Illuminate\Support\Facades\DB;

class MatchController extends Controller
{
    public function index()
    {
        // 過去の試合結果を取得してビューに渡す
        $matches = Match::all();
        return view('matches.index', compact('matches'));
    }

    public function store(Request $request)
    {
    $match = new Match();
    $match->team_id = $request->team_id; // チームID
    $match->opponent_team_id = $request->opponent_team_id; // 対戦相手のチームID
    // その他の試合データを保存
    $match->save();

    return redirect()->route('matches.index')->with('success', '試合データが保存されました。');
    }

    public function search(Request $request)
    {
    // 日付と対戦相手をリクエストから取得
    $date = $request->input('date');
    $opponent = $request->input('opponent');

    // 日付と対戦相手が入力されている場合は、該当する試合のみを取得する
    if ($date && $opponent) {
        $matches = Match::where('date', $date)->where('opponent', $opponent)->get();
    } elseif ($date) {
        $matches = Match::where('date', $date)->get();
    } elseif ($opponent) {
        $matches = Match::where('opponent', $opponent)->get();
    } else {
        // 日付と対戦相手がどちらも入力されていない場合は全試合を取得する
        $matches = Match::all();
    }

    // 検索結果をビューに渡す
    return view('matches.index', compact('matches'));
    }

    public function showForm()
    {
        // フォームを表示するビューを返す
        return view('matches.index');
    }

    public function secondView()
    {
        // ここでビューを表示する処理を記述
        return view('second'); // ビューの名前は 'second.blade.php' と仮定します
    }

    public function thirdView()
    {
        // ここでビューを表示する処理を記述
        return view('third');
    }

    public function saveMatchData(Request $request)
    {
        // リクエストから必要なデータを取得
        $opponentTeamId = $request->input('opponentTeamId');
        $team1Formation = $request->input('team1Formation');
        $team2Formation = $request->input('team2Formation');
        $team1Players = $request->input('team1Players');
        $team2Players = $request->input('team2Players');

        // matchesテーブルにデータを保存
        $match = new Match();
        $match->date = $matchDate;
        $match->opponent_team = $opponentTeamId;
        $match->team1_formation = $team1Formation;
        $match->team2_formation = $team2Formation;
        // 他の情報も適切に保存
        $match->save();

        // playersテーブルにデータを保存
        // チーム1の選手情報を保存
        foreach ($team1Players as $playerData) {
            $player = new Player();
            $player->match_id = $match->id;
            $player->team_id = $team1Id; // チーム1のIDを適切に設定する
            $player->player_number = $playerData['player_number'];
            $player->foot = $playerData['foot']; // 選手情報から利き足を取得
            $player->goals = $playerData['goals']; // 選手情報からゴール数を取得
            $player->feature = $playerData['feature']; // 選手情報から特徴を取得
            // 他の選手情報も適切に保存する
            $player->save();
        }

        // チーム2の選手情報を保存
        foreach ($team2Players as $playerData) {
            $player = new Player();
            $player->match_id = $match->id;
            $player->team_id = $team2Id; // チーム2のIDを適切に設定する
            $player->player_number = $playerData['player_number'];
            $player->foot = $playerData['foot']; // 選手情報から利き足を取得
            $player->goals = $playerData['goals']; // 選手情報からゴール数を取得
            $player->feature = $playerData['feature']; // 選手情報から特徴を取得
            // 他の選手情報も適切に保存する
            $player->save();
        }

        // 必要に応じてレスポンスを返す
        return response()->json(['message' => 'Match data saved successfully']);
    }
}