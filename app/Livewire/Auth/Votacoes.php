<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\Votacao;
use Livewire\WithPagination;

class Votacoes extends Component
{
    use WithPagination;

    public $parties = [];
    public $confirmingDelete = false;
    //public $votings = [];

    public function mount()
    {
        null;
    }

    public function render()
    {
        $votings = Votacao::paginate(10);

        return view('livewire.auth.votacoes', [
            'votings' => $votings,
        ])->layout('components.layouts.app');
    }
}
