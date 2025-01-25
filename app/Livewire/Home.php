<?php

namespace App\Livewire;

use App\Models\Partido;
use App\Services\SenadorServices;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Home extends Component
{
    public function moun()
    {}

    public function render()
    {
        return view('livewire.home')
            ->layout('components.layouts.guest')
            ->title(' - Impeachment de Alexandre de Moraes');
    }
}
