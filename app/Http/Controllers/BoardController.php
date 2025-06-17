<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
//todos los tableros
    public function index()
    {
        $boards = Board::where('user_id', Auth::id())->get();
        return response()->json($boards);
    }

    public function show(Game $game)
    {
        $board = Board::where('game_id', $game->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'grid' => $board->grid,
        ]);
    }


}
