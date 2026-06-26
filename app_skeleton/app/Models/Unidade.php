<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    protected $table = 'unidades';

    protected $fillable = [
        'nome', 'cidade', 'endereco', 'status',
    ];

    public function ordensServico()
    {
        return $this->hasMany(OrdemServico::class, 'unidade_id');
    }

    public function scopeAtivas($query)
    {
        return $query->where('status', 'ativa');
    }
}
