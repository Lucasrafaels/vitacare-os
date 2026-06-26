<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrdemServico extends Model
{
    protected $table = 'ordens_servico';

    protected $fillable = [
        'codigo', 'profissional_id', 'unidade_id', 'atividade_id', 'gestor_id',
        'data_agendamento', 'hora_agendamento', 'observacoes', 'status',
        'iniciado_em', 'concluido_em',
        'tipo_intervencao', 'resolucao', 'contato_local', 'ficha_obs',
        'motivo_nao_execucao',
    ];

    protected $casts = [
        'data_agendamento' => 'date',
        'iniciado_em'       => 'datetime',
        'concluido_em'      => 'datetime',
    ];

    /* ----------------------------- Relacionamentos ----------------------------- */

    public function profissional()
    {
        return $this->belongsTo(Profissional::class, 'profissional_id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
    }

    public function atividade()
    {
        return $this->belongsTo(Atividade::class, 'atividade_id');
    }

    public function gestor()
    {
        return $this->belongsTo(Profissional::class, 'gestor_id');
    }

    /* --------------------------------- Regras ---------------------------------- */

    public static function gerarCodigo(): string
    {
        // Geração segura contra concorrência: faz lock pessimista
        // e busca o maior número sequencial já gravado para o ano.
        $ano = now()->year;

        return DB::transaction(function () use ($ano) {
            $prefix = "OS-{$ano}-";

            $maxSeq = static::where('codigo', 'like', $prefix.'%')
                ->lockForUpdate()
                ->get(['codigo'])
                ->map(function ($row) {
                    $partes = explode('-', $row->codigo);
                    return (int) end($partes);
                })
                ->max();

            $seq = ($maxSeq ?? 0) + 1;

            return sprintf('OS-%d-%04d', $ano, $seq);
        });
    }

    public function podeIniciar(): bool
    {
        return $this->status === 'nao_iniciado';
    }

    public function podeConcluir(): bool
    {
        return $this->status === 'iniciado';
    }

    public function podeMarcarNaoExecutada(): bool
    {
        return in_array($this->status, ['nao_iniciado', 'iniciado']);
    }

    public function iniciar(): void
    {
        if (! $this->podeIniciar()) {
            throw new \RuntimeException('Esta OS não pode ser iniciada no status atual.');
        }

        $this->update([
            'status'      => 'iniciado',
            'iniciado_em' => now(),
        ]);
    }

    public function concluir(array $ficha): void
    {
        if (! $this->podeConcluir()) {
            throw new \RuntimeException('Esta OS precisa estar em andamento para ser concluída.');
        }

        $this->update([
            'status'           => 'concluido',
            'concluido_em'     => now(),
            'tipo_intervencao' => $ficha['tipo_intervencao'],
            'resolucao'        => $ficha['resolucao'],
            'contato_local'    => $ficha['contato_local'],
            'ficha_obs'        => $ficha['ficha_obs'] ?? null,
        ]);
    }

    public function marcarNaoExecutada(string $motivo): void
    {
        if (! $this->podeMarcarNaoExecutada()) {
            throw new \RuntimeException('Esta OS não pode mais ser marcada como não executada.');
        }

        $this->update([
            'status'               => 'nao_executado',
            'motivo_nao_execucao'  => $motivo,
        ]);
    }

    public function duplicar(): self
    {
        $nova = $this->replicate([
            'status', 'iniciado_em', 'concluido_em',
            'tipo_intervencao', 'resolucao', 'contato_local', 'ficha_obs',
            'motivo_nao_execucao', 'created_at', 'updated_at',
        ]);

        $nova->codigo = static::gerarCodigo();
        $nova->status = 'nao_iniciado';
        $nova->save();

        return $nova;
    }

    /* --------------------------------- Scopes ----------------------------------- */

    public function scopeDoProfissional($query, int $profissionalId)
    {
        return $query->where('profissional_id', $profissionalId);
    }

    public function scopeDoDia($query, $data)
    {
        return $query->whereDate('data_agendamento', $data);
    }
}
