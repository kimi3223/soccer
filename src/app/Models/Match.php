<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'opponent', 'result', 'position', 'comment'];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }
}
