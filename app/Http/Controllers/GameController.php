<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\User;
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
            'current_turn'  => $user->id, // Primer turno es el de la persona que crea el juego
        ]);

        $ships = $this->generateRandomShips();

        Board::create([
            'game_id' => $game->id,
            'user_id' => $user->id,
            'grid'    => $ships,
        ]);

        // Carga relaciones para la vista
        $game->load(['boards.user', 'moves.user']);

        return Inertia::location(route('games.show', $game));
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
        $game->load(['boards.user', 'moves.user']);

        return Inertia::render('Games/Show', [
            'game' => $game,
        ]);
    }
    public function apiShow(Game $game)
    {
        $game->load('boards.user', 'moves.user');

        return response()->json([
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

        if ($game->player_one_id === $user->id) {
            return back()->with('error', 'No puedes unirte a tu propio juego.');
        }

        if ($game->player_two_id || $game->boards->count() >= 2) {
            return back()->with('error', 'Este juego ya está lleno.');
        }

        $game->update([
            'player_two_id' => $user->id,
            'status'        => 'playing',
        ]);

        $ships = $this->generateRandomShips();

        Board::create([
            'game_id' => $game->id,
            'user_id' => $user->id,
            'grid'    => $ships,
        ]);

        $game->load(['boards.user', 'moves.user']);

        return Inertia::location(route('games.show', $game));
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

    public function dataOnGamesPlayed(Request $request){
        $user = Auth::user();
        $games = Game::where(function($query) use ($user) {
            $query->where('player_one_id', $user->id)
                ->orWhere('player_two_id', $user->id);
        })
        ->Where('status', 'finished')
        ->get();

        $wins = $games->where('winner_id', $user->id)->count();
        $loses = $games->where('winner_id', '!=', $user->id)->count();
        return Inertia::render('Statistics', [
            'wins' => $wins,
            'losses' => $loses,
        ]);
    }

    public function gamesByResult(Request $request){
        $user = Auth::user();
        $type = $request->query('type');

        $query = Game::where(function ($q) use ($user) {
            $q->where('player_one_id', $user->id)->orWhere('player_two_id', $user->id);
        })->where('status', 'finished')->whereNotNull('winner_id');

        if ($type === 'won') {
            $query->where('winner_id', $user->id);
        } elseif ($type === 'lost') {
            $query->where('winner_id', '!=', $user->id);
        } else {
            return response()->json([
                'error' => 'Tipo inválido. Usa "won" o "lost".'
            ], 400);
        }
        $games = $query->get();

        $results = $games->map(function ($game) use ($user) {
            $isPlayerOne = $game->player_one_id == $user->id;
            $opponentId = $isPlayerOne ? $game->player_two_id : $game->player_one_id;

            $opponent = User::find($opponentId);

            return [
                'id' => $game->id,
                'me' => $user->name,
                'opponent' => optional($opponent)->name ?? 'Desconocido',
            ];
        });

        return response()->json([
            'results' => $results,
        ]);
    }
}
