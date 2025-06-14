<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Move;
use App\Models\Game;
use App\Models\Board;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MoveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Registrar un disparo contra un juego.
     */
    public function store(Request $request, Game $game)
    {
        $user = $request->user();

        // 1) Validar estado 'playing'
        if ($game->status !== 'playing') {
            return redirect()->back()->with('error', 'El juego no está en curso.');
        }

        // 2) Validar turno: solo quien tenga current_turn puede disparar
        if ($game->current_turn !== $user->id) {
            return redirect()->back()->with('error', 'No es tu turno.');
        }

        // 3) Validar coordenadas
        $data = $request->validate([
            'x' => 'required|integer|between:0,7',
            'y' => 'required|integer|between:0,7',
        ]);

        // 4) Evitar disparo duplicado
        if ($game->moves()
            ->where('user_id', $user->id)
            ->where('x', $data['x'])
            ->where('y', $data['y'])
            ->exists()
        ) {
            return redirect()->back()->with('error', 'Ya atacaste esa casilla.');
        }

        // 5) Determinar hit/miss en el tablero del oponente
        $opponentId = $user->id === $game->player_one_id
            ? $game->player_two_id
            : $game->player_one_id;

        $opponentBoard = Board::where('game_id', $game->id)
            ->where('user_id', $opponentId)
            ->firstOrFail();

        // grid es array bidimensional [8][8] con 1=barco, 0=agua
        $cell = $opponentBoard->grid[$data['x']][$data['y']] ?? 0;
        $hit = $cell === 1;

        // 6) Crear move con resultado
        $game->moves()->create([
            'user_id' => $user->id,
            'x'       => $data['x'],
            'y'       => $data['y'],
            'result'  => $hit ? 'hit' : 'miss',
        ]);

        // 7) Alternar turno
        $nextTurn = $user->id === $game->player_one_id
            ? $game->player_two_id
            : $game->player_one_id;
        $game->update(['current_turn' => $nextTurn]);

        // 8) Redirect Inertia al show del juego
        return to_route('games.show', $game);
    }

    /**
     * Listar movimientos de la partida.
     */
    public function index(Game $game)
    {
        $moves = $game->moves()->with('user')->get();
        return response()->json(['moves' => $moves]);
    }
}
