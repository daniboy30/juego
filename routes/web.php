<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoveController;
use Inertia\Inertia;



Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // RUTAS JUEGO
    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    Route::post('/games', [GameController::class, 'store'])->name('games.store');

    // Unirse y ver la partida
    Route::put('/games/{game}', [GameController::class, 'update'])->name('games.update');
    Route::get('/games/{game}', [GameController::class, 'show'])->name('games.show');
    Route::post('/games/{game}/moves', [MoveController::class, 'store'])->name('moves.store');
    Route::get('/api/games/{game}', [GameController::class, 'apiShow'])->name('games.api');
    // (Opcional) Eliminar partida
    Route::delete('/games/{game}', [GameController::class, 'destroy'])->name('games.destroy');

    Route::post('/games/{game}/leave', [GameController::class, 'leave'])->name('games.leave');
    Route::put('/games/{game}/cancel', [GameController::class, 'cancel'])->name('games.cancel');

    Route::get('/api/games-played', [GameController::class, 'dataOnGamesPlayed'])->name('games.played');
    Route::get('/api/games-played/results', [GameController::class, 'gamesByResult'])->name('games.results');

});

require __DIR__.'/auth.php';
