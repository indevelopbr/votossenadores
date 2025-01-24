<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Senador extends Model
{
    use HasFactory, Notifiable, SoftDeletes, LogsActivity;

    protected $table = 'senadores';

    protected $fillable = [
        'id',
        'codigo_publico_na_leg_atual',
        'nome',
        'nome_completo',
        'sexo',
        'forma_tratamento',
        'url_foto',
        'url_pagina',
        'email',
        'telefone',
        'partido_id',
        'uf',
        'membro_mesa',
        'membro_lideranca',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('senador');
    }
}
