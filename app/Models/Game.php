<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Board;
use App\Models\Move;

class Game extends Model
{
    use HasFactory;

    // Campos que pueden asignarse por mass assignment
    protected $fillable = [
        'player_one_id',
        'player_two_id',
        'status',
        'current_turn',
        'winner_id',
    ];
    /**
     * El usuario que creó la partida
     */
    public function playerOne()
    {
        return $this->belongsTo(User::class, 'player_one_id');
    }

    /**
     * El usuario que se unió a la partida
     */
    public function playerTwo()
    {
        return $this->belongsTo(User::class, 'player_two_id');
    }

    /**
     * El usuario que resultó ganador
     */
    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    /**
     * Los tableros de ambos jugadores en esta partida
     */
    public function boards()
    {
        return $this->hasMany(Board::class);
    }

    /**
     * Todos los disparos realizados en esta partida
     */
    public function moves()
    {
        return $this->hasMany(Move::class);
    }
}
