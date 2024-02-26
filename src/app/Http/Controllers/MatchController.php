<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Match;
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
    // フォームデータを受け取り、マッチモデルを使用してデータベースに保存する
    $match = new Match();
    $match->date = $request->date;
    $match->opponent = $request->opponent;

     // フォームから送信された試合結果とポジションのデータを処理し、$matchに設定する
    $match->result1 = $request->results[0]; // 最初の試合の結果
    $match->position1 = $request->positions[0]; // 最初の試合のポジション

    // 2試合目以降の結果とポジションを設定
    $match->result2 = $request->results[1] ?? null;
    $match->position2 = $request->positions[1] ?? null;
    $match->result3 = $request->results[2] ?? null;
    $match->position3 = $request->positions[2] ?? null;
    $match->result4 = $request->results[3] ?? null;
    $match->position4 = $request->positions[3] ?? null;

    $match->save();

    // マッチのリストページにリダイレクトする
    return redirect()->route('matches.index')->with('success', '試合を記録しました');
}

    public function search(Request $request)
    {
        $date = $request->input('date');
        $opponent = $request->input('opponent');

        // 検索条件に基づいて試合結果を取得する処理を行う

        return redirect()->route('matches.index')->with('date', $date)->with('opponent', $opponent);
    }

}