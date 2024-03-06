<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'opponent_team', 'result'];

    public function formations()
    {
        return $this->hasMany(Formation::class);
    }
}
