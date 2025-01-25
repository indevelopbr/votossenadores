<?php

use App\Livewire\Auth\CriarVotacao;
use App\Livewire\Auth\EditarVoto;
use App\Livewire\Auth\Votacoes;
use App\Livewire\Home;
use App\Livewire\Partidos;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('cms')->group(function () {
    Route::get('/votacoes', Votacoes::class)->name('votacoes');
    Route::get('/votacoes/criar', CriarVotacao::class)->name('votacoes.criar');
    
    Route::get('/votacoes/{votacao}/votos', EditarVoto::class)->name('votacoes.votos');
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
