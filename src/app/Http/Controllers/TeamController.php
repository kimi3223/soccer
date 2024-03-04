<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
     // チーム名登録フォームを表示
    public function create()
    {
        $teams = Team::pluck('name', 'id'); // teamsテーブルからチーム名のみを取得

        return view('teams.create', compact('teams'));
    }

    // チーム名を保存
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams',
        ]);

        Team::create([
            'name' => $request->name,
        ]);

        return redirect()->route('teams.create')->with('success', 'チーム名を保存しました。');
    }

    // フォーメーションを保存
    public function storeFormation(Request $request, $teamId)
    {
    $request->validate([
        'formation' => 'required|string|max:255',
    ]);

    $team = Team::findOrFail($teamId);
    $team->formation = $request->formation;
    $team->save();

    return redirect()->back()->with('success', 'フォーメーションを保存しました。');
    }

    // インデックスページを表示
    public function index()
    {
        $teams = Team::all(); // すべてのチーム情報を取得

        return view('index', compact('teams'));
    }

}
