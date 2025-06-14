<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use App\Models\Game;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Lista de los juegos disponibles o en los que participa el usuario.
     */
    public function index()
    {
        $user = Auth::user();

        $games = Game::with('boards.user')
            ->where('status', 'waiting')
            ->orWhere(function ($q) use ($user) {
                $q->where('player_one_id', $user->id)
                    ->orWhere('player_two_id', $user->id);
            })
            ->get();

        return response()->json([
            'games' => $games,
        ]);
    }

    /**
     * Crea un nuevo juego, genera el tablero del primer jugador
     * y redirige a la vista show para ver tu tablero.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $game = Game::create([
            'player_one_id' => $user->id,
            'status'        => 'waiting',
        ]);

        $ships = $this->generateRandomShips();

        Board::create([
            'game_id' => $game->id,
            'user_id' => $user->id,
            'grid'    => $ships,
        ]);

        // Carga relaciones para la vista
        $game->load(['boards.user', 'moves.user']);

        return redirect()->route('games.show', $game);
    }

    /**
     * Genera un arreglo de 15 posiciones únicas en un tablero 8x8 (A1-H8).
     */
    private function generateRandomShips()
    {
        $ships = [];
        $positions = [];

        while (count($ships) < 15) {
            $row = chr(rand(65, 72)); // Letras A-H
            $col = rand(1, 8);        // Columnas 1-8
            $pos = $row . $col;

            if (!in_array($pos, $positions)) {
                $ships[]     = $pos;
                $positions[] = $pos;
            }
        }

        return $ships;
    }

    /**
     * Muestra la partida, con tableros y movimientos.
     */
    public function show(Game $game)
    {
        $game->load('boards.user', 'moves.user');

        return Inertia::render('Games/Show', [
            'game' => $game,
        ]);
    }

    /**
     * Se une el segundo jugador al juego en espera, se crea su tablero
     * y redirige a la vista show.
     */
    public function update(Request $request, Game $game)
    {
        $user = $request->user();
        $game->load('boards');

        // 1) No permitir que el creador se una
        if ($game->player_one_id === $user->id) {
            return redirect()->back()->with('error', 'No puedes unirte a tu propio juego.');
        }

        // 2) No permitir más de 2 jugadores
        if ($game->player_two_id || $game->boards->count() >= 2) {
            return redirect()->back()->with('error', 'Este juego ya está lleno.');
        }

        // 3) Actualizar el juego para asignar player_two y estado
        $game->update([
            'player_two_id' => $user->id,
            'status'        => 'playing',
        ]);

        // 4) Todo bien: agregar tablero del segundo jugador
        $ships = $this->generateRandomShips();
        Board::create([
            'game_id' => $game->id,
            'user_id' => $user->id,
            'grid'    => $ships,
        ]);

        // Redirige a la vista show de Inertia
        return to_route('games.show', $game);
    }

    public function destroy(Game $game)
    {
        $this->authorize('delete', $game);

        if ($game->status === 'waiting') {
            $game->delete();
            return redirect()->route('games.index');
        }

        return back()->with('error', 'No puedes eliminar un juego en curso.');
    }
}
