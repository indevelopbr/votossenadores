<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Partido extends Model
{
    use HasFactory, Notifiable, SoftDeletes, LogsActivity;

    protected $fillable = [
        'id',
        'sigla',
        'nome',
        'logo',
        'site',
        'facebook',
        'instagram',
        'twitter',
        'manual_logo',
        'manual_site',
        'manual_facebook',
        'manual_instagram',
        'manual_twitter',
        'data_criacao',
        'data_extincao',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('partido');
    }

    public function senadores()
    {
        return $this->hasMany(Senador::class);
    }
}
