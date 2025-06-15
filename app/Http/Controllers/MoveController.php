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

        // 4) Convertir coordenadas numéricas a posición tipo "A5"
        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        $pos = $letters[$data['x']] . ($data['y'] + 1);

        // 5) Evitar disparo duplicado
        if ($game->moves()
            ->where('user_id', $user->id)
            ->where('x', $data['x'])
            ->where('y', $data['y'])
            ->exists()
        ) {
            return redirect()->back()->with('error', 'Ya atacaste esa casilla.');
        }

        // 6) Buscar el tablero del oponente
        $opponentId = $user->id === $game->player_one_id
            ? $game->player_two_id
            : $game->player_one_id;

        $opponentBoard = Board::where('game_id', $game->id)
            ->where('user_id', $opponentId)
            ->firstOrFail();

        // 7) Determinar si es acierto
        $grid = $opponentBoard->grid; // array tipo ["A1", "D3", ...]
        $hit = in_array($pos, $grid);

        // 8) Registrar el movimiento
        $game->moves()->create([
            'user_id' => $user->id,
            'x' => $data['x'],
            'y' => $data['y'],
            'result' => $hit ? 'hit' : 'miss',
        ]);

        // 9) Verificar si ganó (si acertó todos los barcos del oponente)
        if ($hit) {
            $hits = $game->moves()
                ->where('user_id', $user->id)
                ->where('result', 'hit')
                ->get();

            $hitPositions = $hits->map(function ($move) use ($letters) {
                return $letters[$move->x] . ($move->y + 1);
            })->toArray();

            $remaining = array_diff($grid, $hitPositions);

            if (count($remaining) === 0) {
                $game->update([
                    'status' => 'finished',
                    'winner_id' => $user->id,
                    'current_turn' => null,
                ]);

                return response()->json([
                    'message' => '¡Has ganado la partida!',
                    'result' => 'hit',
                    'winner' => true,
                ]);
            }
        }
        $nextTurn = $user->id === $game->player_one_id
            ? $game->player_two_id
            : $game->player_one_id;

        $game->update(['current_turn' => $nextTurn]);

        return response()->json([
            'message' => 'Movimiento registrado correctamente.',
            'result' => $hit ? 'hit' : 'miss',
            'winner' => false,
        ]);
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
