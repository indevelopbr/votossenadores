<?php

use App\Livewire\Home;
use App\Livewire\Partidos;
use Illuminate\Support\Facades\Route;


Route::get('/', Home::class)->name('home');
Route::get('/partidos', Partidos::class)->name('partidos');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
