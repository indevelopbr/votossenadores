<?php

use App\Livewire\Auth\EditarVoto;
use App\Livewire\Home;
use App\Livewire\Partidos;
use App\Livewire\Voting\ListVotings;
use App\Livewire\Voting\Vote\ListVote;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('cms')->group(function () {
    Route::get('/votacoes', ListVotings::class)->name('votacoes');
    Route::get('/votacoes/{voting}/votos', ListVote::class)->name('votacoes.votos');
});

Route::get('/', Home::class)->name('home');
Route::get('/partidos', Partidos::class)->name('partidos');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
