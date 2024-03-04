<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    protected $table = 'teams'; // teamsテーブルを使用することを指定

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function homeMatches()
    {
        return $this->hasMany(Match::class, 'team1_id');
    }

    public function awayMatches()
    {
        return $this->hasMany(Match::class, 'team2_id');
    }
}
