<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    protected $fillable = ['player_number', 'foot', 'goals', 'feature', 'team_id'];

    // デフォルト値を設定
    protected $attributes = [
        'team_id' => null,
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
