<?php

namespace App\Livewire;

use App\Models\Voting;
use Livewire\Component;

class Home extends Component
{
    public function moun()
    {}

    public function render()
    {
        $voting = Voting::first();

        return view('livewire.home', [
                'voting' => $voting,
            ])
            ->layout('components.layouts.guest')
            ->title(' - Impeachment de Alexandre de Moraes');
    }
}
