<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Game;
use App\Models\User;

class Board extends Model
{
    use HasFactory;
    protected $fillable = [
        'game_id',
        'user_id',
        'grid',
    ];

    protected $casts = [
        'grid' => 'array',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
