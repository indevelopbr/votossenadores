<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Votacao extends Model
{
    use HasFactory, Notifiable, SoftDeletes, LogsActivity;

    protected $table = 'votacoes';

    protected $fillable = [
        'codigo_votacao',
        'sigla_casa_colegiado',
        'codigo_reuniao',
        'data_hora_inicio_reuniao',
        'numero_reuniao_colegiado',
        'tipo_reuniao',
        'codigo_colegiado',
        'sigla_colegiado',
        'nome_colegiado',
        'codigo_parlamentar_presidente',
        'nome_parlamentar_presidente',
        'identificacao_materia',
        'descricao_identificacao_materia',
        'descricao_votacao',
        'total_votos_sim',
        'total_votos_nao',
        'total_votos_abstencao',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('votacao');
    }

    public function votos()
    {
        return $this->hasMany(Voto::class, 'votacao_id', 'id');
    }
}
