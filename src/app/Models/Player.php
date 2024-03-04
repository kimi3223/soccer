<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    protected $fillable = ['player_number', 'foot', 'goals', 'feature', 'team_id'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
