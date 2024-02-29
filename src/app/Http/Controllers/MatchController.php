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
}