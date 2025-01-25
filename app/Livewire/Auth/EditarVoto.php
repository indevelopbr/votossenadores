<?php

namespace App\Livewire\Auth;

use App\Models\Voto;
use Livewire\Component;

class EditarVoto extends Component
{
    public $parties = [];
    public $ufs =[];
    public $votos = [];

    public function mount()
    {
        $this->votos = Voto::with('senador')->get();   
    }

    public function updated($property, $value)
    {
        dd($property, $value);
    }
    
    public function render()
    {
        return view('livewire.auth.editar-voto');
    }
}
