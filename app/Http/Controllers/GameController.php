<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Services\BoardGenerator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    protected $boardGenerator;

    public function __construct(BoardGenerator $boardGenerator)
    {
        $this->middleware('auth');
        $this->boardGenerator = $boardGenerator;
    }

    //lista de los juegos
    public function index()
    {
        $user = Auth::user();

        $games = Game::where('status', 'waiting')
            ->orWhere(function($q) use ($user) {
                $q->where('player_one_id', $user->id)
                    ->orWhere('player_two_id', $user->id);
            })
            ->get();

        return Inertia::render('Games/Index', compact('games'));
    }

    // se crea el nuevo juego y tambien el tablaro
    public function store(Request $request)
    {
        $user = Auth::user();
        $game = Game::create([
            'player_one_id' => $user->id,
            'status'        => 'waiting',
        ]);
        // se crea el tablero
        $this->boardGenerator->generate($user, $game);
        return redirect()->route('games.show', $game);
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
        // Genera tablero para el segundo jugador
        $this->boardGenerator->generate($user, $game);
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
