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
        // マッチデータを保存する処理
        Match::create([
            'date' => $request->input('date'), // 試合日
            'opponent_team' => $request->input('opponent_team'), // 相手チーム名
            'result' => $request->input('result'), // 試合結果
            'team_id' => $request->input('team_id'), // 自チームID
        ]);

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
        // リクエストからデータを取得
        $opponentTeamId = $request->input('opponent_team');
        $matchDate = $request->input('date');
        $team1Formation = $request->input('team1_formation');
        $team2Formation = $request->input('team2_formation');
        $selectedTeamId = $request->input('selected_team_id');

        // データベースに保存
        $match = new Match();
        $match->opponent_team_id = $opponentTeamId;
        $match->match_date = $matchDate;
        $match->team1_formation = $team1Formation;
        $match->team2_formation = $team2Formation;
        $match->selected_team_id = $selectedTeamId;
        $match->save();

        // チーム1の選手情報を保存
        foreach ($team1Players as $playerData) {
            $player = new Player();
            $player->match_id = $match->id;
            $player->team_id = $playerData['teamId']; // チームIDを適切に設定する
            $player->player_number = $playerData['player_number'];
            $player->foot = $playerData['foot'];
            $player->goals = $playerData['goals'];
            $player->feature = $playerData['feature'];
            $player->save();
        }

         // チーム2の選手情報を保存
        foreach ($team2Players as $playerData) {
            $player = new Player();
            $player->match_id = $match->id;
            $player->team_id = $playerData['teamId']; // チームIDを適切に設定する
            $player->player_number = $playerData['player_number'];
            $player->foot = $playerData['foot'];
            $player->goals = $playerData['goals'];
            $player->feature = $playerData['feature'];
            $player->save();
        }

        // 必要に応じてレスポンスを返す
        return response()->json(['message' => 'Match data saved successfully']);
        }

    public function savePlayerData(Request $request)
        {
        // バリデーションを行う場合はここで行う（日付の形式など）

        // フォームから送信された日付を取得
        $matchDate = $request->input('match_date');

        // matchesテーブルに新しいレコードを作成し、日付を保存
        $match = new Match();
        $match->date = now(); // 現在の日時を取得してdateカラムに挿入
        $match->opponent_team = $request->input('team_id');
        $match->result = $request->input('result');
        $match->save();

        // リダイレクトやレスポンスを返すなど適切な処理を行う
    }
}