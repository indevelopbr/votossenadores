<?php

namespace App\Livewire\Auth;

use App\Models\Senador;
use App\Models\Votacao;
use Livewire\Component;

class CriarVotacao extends Component
{
    public $codigo_votacao;
    public $sigla_casa_colegiado;
    public $codigo_reuniao;
    public $data_hora_inicio_reuniao;
    public $numero_reuniao_colegiado;
    public $tipo_reuniao;
    public $codigo_colegiado;
    public $sigla_colegiado;
    public $nome_colegiado;
    public $codigo_parlamentar_presidente;
    public $nome_parlamentar_presidente;
    public $identificacao_materia;
    public $descricao_identificacao_materia;
    public $descricao_votacao;
    public $total_votos_sim = 0;
    public $total_votos_nao = 0;
    public $total_votos_abstencao = 0;

    public function submit()
    {
        $votacao = Votacao::create([
            'codigo_votacao' => $this->codigo_votacao,
            'sigla_casa_colegiado' => $this->sigla_casa_colegiado,
            'codigo_reuniao' => $this->codigo_reuniao,
            'data_hora_inicio_reuniao' => $this->data_hora_inicio_reuniao,
            'numero_reuniao_colegiado' => $this->numero_reuniao_colegiado,
            'tipo_reuniao' => $this->tipo_reuniao,
            'codigo_colegiado' => $this->codigo_colegiado,
            'sigla_colegiado' => $this->sigla_colegiado,
            'nome_colegiado' => $this->nome_colegiado,
            'codigo_parlamentar_presidente' => $this->codigo_parlamentar_presidente,
            'nome_parlamentar_presidente' => $this->nome_parlamentar_presidente,
            'identificacao_materia' => $this->identificacao_materia,
            'descricao_identificacao_materia' => $this->descricao_identificacao_materia,
            'descricao_votacao' => $this->descricao_votacao,
            'total_votos_sim' => $this->total_votos_sim,
            'total_votos_nao' => $this->total_votos_nao,
            'total_votos_abstencao' => $this->total_votos_abstencao,
        ]);

        $senadores = Senador::all();

        foreach ($senadores as $senador) {
            $votacao->votos()->create([
                'votacao_id'                => $votacao->id,
                'senador_id'                => $senador->id,
                'partido_id'                => $senador->partido_id,
                'sigla_casa_parlamentar'    => null,
                'qualidade_voto'            => null,
                'voto_presidente'           => false,
            ]);
        }

        session()->flash('message', 'Votação criada com sucesso!');

        // Resetar os campos do formulário
        $this->reset();
    }

    public function render()
    {
        return view('livewire.auth.criar-votacao');
    }
}
