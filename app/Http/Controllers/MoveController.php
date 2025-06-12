<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Move;
use App\Models\Game;
use App\Models\Board;
use Illuminate\Support\Facades\Auth;

class MoveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

   // se registra el disparo
    public function store(Request $request, Game $game)
    {
        $data = $request->validate([
            'x' => 'required|integer|between:0,7',
            'y' => 'required|integer|between:0,7',
        ]);

        // se crea el disparo
        $move = Move::create([
            'game_id' => $game->id,
            'user_id' => Auth::id(),
            'x'       => $data['x'],
            'y'       => $data['y'],
        ]);

        // Determinar hit/miss mirando el tablero del oponente
        $opponentId = Auth::id() === $game->player_one_id
            ? $game->player_two_id
            : $game->player_one_id;

        $opponentBoard = Board::where('game_id', $game->id)
            ->where('user_id', $opponentId)
            ->firstOrFail();

        $cell = $opponentBoard->grid[$data['x']][$data['y']] ?? 0;
        $status = $cell === 1 ? 'hit' : 'miss';

        // Actualizar el disparo
        $move->update(['result' => $status]);

        return response()->json(['result' => $status]);
    }

    // movimientos de la partida
    public function index(Game $game)
    {
        $moves = $game->moves()->get();
        return response()->json($moves);
    }
}
