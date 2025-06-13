<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Services\BoardGenerator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //lista de los juegos
    public function index()
    {
        $user = Auth::user();

        $games = Game::with('boards.user')
            ->where('status', 'waiting')
            ->orWhere(function($q) use ($user) {
                $q->where('player_one_id', $user->id)
                    ->orWhere('player_two_id', $user->id);
            })
            ->get();

        return response()->json([
            'games' => $games,
        ]);
    }

    // se crea el nuevo juego y tambien el tablaro
    public function store(Request $request)
    {
        $user = Auth::user();

        $game = Game::create([
            'player_one_id' => $user->id,
            'status' => 'waiting',
        ]);
        $ships = $this->generateRandomShips();

        Board::create([
            'game_id' => $game->id,
            'user_id' => $user->id,
            'grid' => $ships,
        ]);
        $game->load('boards.user');;

        return response()->json([
            "message" => "Juego creado",
            "newGame" => $game,
        ]);
    }

    private function generateRandomShips()
    {
        $ships = [];
        $positions = [];
        while (count($ships) < 15) {
            $row = chr(rand(65, 72)); // A-H
            $col = rand(1, 8);
            $pos = $row . $col;
            if (!in_array($pos, $positions)) {
                $ships[] = $pos;
                $positions[] = $pos;
            }
        }
        return $ships;
    }

    // muestra el detalle del game
    public function show(Game $game)
    {
        $game->load(['boards', 'moves']);

        return Inertia::render('Games/Show', compact('game'));
    }

    //unete al juego que esta en espera y se crea tu tablero
    public function update(Request $request, Game $game)
    {
        $user = Auth::user();
        if ($game->status !== 'waiting') {
            return back()->with('error', 'El juego ya inició.');
        }

        $game->update([
            'player_two_id' => $user->id,
            'status' => 'playing',
        ]);
        $ships = $this->generateRandomShips();
        Board::create([
            'game_id' => $game->id,
            'user_id' => $user->id,
            'grid'    => $ships,
        ]);
        return redirect()->route('games.show', $game);
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
