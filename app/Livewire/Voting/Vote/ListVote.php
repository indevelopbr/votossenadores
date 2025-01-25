<?php

namespace App\Livewire\Voting\Vote;

use App\Models\Senator;
use App\Models\Vote;
use App\Models\Voting;
use Livewire\Component;
use Livewire\WithPagination;

class ListVote extends Component
{
    use WithPagination;

    public Voting $voting;

    // Filtros
    public $filterName = '';
    public $filterParty = '';
    public $filterUf = '';

    // Exemplo de array para armazenar os "votos" selecionados via radio
    public $votos = [];

    /**
     * Monta o componente, recebendo a Voting que está sendo visualizada.
     */
    public function mount(Voting $voting)
    {
        $this->voting = $voting;

        // Opcionalmente, podemos popular $votos[] com o status inicial (ex: do banco)
        // para cada registro, se existir:
        $existingVotes = Vote::where('voting_id', $voting->id)->get();
        foreach ($existingVotes as $vote) {
            // Se houver algum campo "position" (A FAVOR, Indefinido, Contra) em Vote...
            // Exemplo:
            $this->votos[$vote->id] = $vote->vote;
        }
    }

    /**
     * Atualiza a páginação sempre que algum filtro mudar, para voltar à página 1.
     */
    public function updatingFilterName()
    {
        $this->resetPage();
    }
    public function updatingFilterParty()
    {
        $this->resetPage();
    }
    public function updatingFilterUf()
    {
        $this->resetPage();
    }

    /**
     * Escuta mudanças de qualquer propriedade e salva, se desejar.
     */
    public function updated($property, $value)
    {
        // Exemplo:
        // Se a propriedade atualizada for algo como "votos.123"
        if (strpos($property, 'votos.') === 0) {
            $voteId = str_replace('votos.', '', $property);

            // Aqui você pode salvar no banco de dados a nova posição marcada:
            // Por exemplo:
            $vote = Vote::find($voteId);

            if ($vote) {
                $vote->vote = $value; // "A FAVOR", "Indefinido" ou "Contra"
                $vote->save();
            }
        }
    }

    public function render()
    {
        // Busca os votos, filtrando pelo voting_id
        $query = Vote::with('senator')
            ->where('voting_id', $this->voting->id);

        // Exemplo de filtros (ajuste se seus campos forem diferentes)
        if ($this->filterName) {
            $query->whereHas('senator', function ($q) {
                $q->where('name', 'like', '%' . $this->filterName . '%');
            });
        }
        if ($this->filterParty) {
            $query->whereHas('senator', function ($q) {
                $q->where('party', 'like', '%' . $this->filterParty . '%');
            });
        }
        if ($this->filterUf) {
            $query->whereHas('senator', function ($q) {
                $q->where('uf', 'like', '%' . $this->filterUf . '%');
            });
        }

        // Paginação ou get() simples
        $votes = $query->get();

        return view('livewire.voting.vote.list-vote', [
            'votes' => $votes,
        ]);
    }
}
