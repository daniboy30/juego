<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Game;
use App\Models\User;

class Move extends Model
{
    use HasFactory;

    /**
     * Los campos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'game_id',
        'user_id',
        'x',
        'y',
        'result', // 'hit' o 'miss'
    ];

    /**
     * Relación con el juego al que pertenece este disparo.
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Relación con el usuario que realizó el disparo.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
