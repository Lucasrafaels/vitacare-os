<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Profissional extends Authenticatable
{
    use Notifiable;

    protected $table = 'profissionais';

    protected $fillable = [
        'nome', 'email', 'senha', 'cargo', 'perfil', 'status',
    ];

    protected $hidden = [
        'senha', 'remember_token',
    ];

    // Laravel's Auth espera "password" por padrão; mapeamos para a coluna "senha".
    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function ehGestor(): bool
    {
        return $this->perfil === 'gestor';
    }

    public function ehFacilitador(): bool
    {
        return $this->perfil === 'facilitador';
    }

    public function ehAtivo(): bool
    {
        return $this->status === 'ativo';
    }

    public function ordensServico()
    {
        return $this->hasMany(OrdemServico::class, 'profissional_id');
    }

    public function ordensGerenciadas()
    {
        return $this->hasMany(OrdemServico::class, 'gestor_id');
    }
}
