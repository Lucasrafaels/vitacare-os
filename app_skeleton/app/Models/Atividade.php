<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atividade extends Model
{
    protected $table = 'atividades';

    protected $fillable = ['nome', 'status'];

    public function ordensServico()
    {
        return $this->hasMany(OrdemServico::class, 'atividade_id');
    }

    public function scopeAtivas($query)
    {
        return $query->where('status', 'ativa');
    }
}
