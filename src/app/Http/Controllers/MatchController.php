<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Match;
use App\Models\Player;
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

    public function saveAllData(Request $request)
{
    // フォームから送信されたデータを取得
    $team1Formation = $request->input('team1Formation');
    $team2Formation = $request->input('team2Formation');
    $team1Players = $request->input('team1Players');
    $team2Players = $request->input('team2Players');

    // マッチの試合情報を保存
    $match = new Match();
    $match->team1_id = $request->input('team1_id');
    $match->team2_id = $request->input('team2_id');
    $match->team1_formation = $team1Formation;
    $match->team2_formation = $team2Formation;
    // その他の試合データを必要に応じて保存
    $match->save();

    // チーム１の選手情報を保存
    foreach ($team1Players as $playerData) {
        Player::create([
            'team_id' => $request->input('team1_id'),
            'player_number' => $playerData['playerNumber'],
            'foot' => $playerData['foot'],
            'goals' => $playerData['goals'],
            'feature' => $playerData['feature']
        ]);
    }

    // チーム２の選手情報を保存
    foreach ($team2Players as $playerData) {
        Player::create([
            'team_id' => $request->input('team2_id'),
            'player_number' => $playerData['playerNumber'],
            'foot' => $playerData['foot'],
            'goals' => $playerData['goals'],
            'feature' => $playerData['feature']
        ]);
    }

    // 成功した場合は適切なレスポンスを返す
    return Redirect::to('/')->with('success', 'Data saved successfully');
}

}