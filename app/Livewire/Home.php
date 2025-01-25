<?php

namespace App\Livewire;

use App\Models\Voting;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $voting = Cache::remember('voting', 60, function () {
            return Voting::with([
                'votes.senator.party',
            ])->first();
        });

        $inFavor    = $voting->votes->where('vote', 'Y');
        $indefinite = $voting->votes->where('vote', 'I');
        $against    = $voting->votes->where('vote', 'N');
    
        return view('livewire.home', [
            'voting'     => $voting,
            'inFavor'    => $inFavor,
            'indefinite' => $indefinite,
            'against'    => $against,
        ])
        ->layout('components.layouts.guest')
        ->title(' - ' . $voting->name);
    }
}
