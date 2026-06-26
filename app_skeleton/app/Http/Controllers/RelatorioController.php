<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\Profissional;
use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RelatorioController extends Controller
{
    public function porProfissional(Request $request)
    {
        $dataIni = $request->query('data_ini') ?: now()->startOfMonth()->toDateString();
        $dataFim = $request->query('data_fim') ?: now()->toDateString();
        $profId  = $request->query('profissional');

        $query = OrdemServico::with(['profissional', 'unidade', 'atividade'])
            ->whereBetween('data_agendamento', [$dataIni, $dataFim]);

        if (! empty($profId)) {
            $query->where('profissional_id', $profId);
        }

        $lista = $query->orderBy('data_agendamento')
            ->get()
            ->map(fn (OrdemServico $os) => [
                'id'                => $os->id,
                'codigo'            => $os->codigo,
                'profissional_nome' => $os->profissional->nome,
                'unidade_nome'      => $os->unidade->nome,
                'atividade_nome'    => $os->atividade->nome,
                'data_agendamento'  => $os->data_agendamento,
                'iniciado_em'       => $os->iniciado_em,
                'concluido_em'      => $os->concluido_em,
                'status'            => $os->status,
            ]);

        return view('relatorios.os-profissional', [
            'lista'         => $lista,
            'data_ini'      => $dataIni,
            'data_fim'      => $dataFim,
            'prof_id'       => $profId,
            'profissionais' => Profissional::where('perfil', 'facilitador')->orderBy('nome')->get(),
        ]);
    }

    public function tempoMedio(Request $request)
    {
        $dataIni = $request->query('data_ini') ?: now()->startOfMonth()->toDateString();
        $dataFim = $request->query('data_fim') ?: now()->toDateString();

        $concluidas = OrdemServico::with('unidade')
            ->where('status', 'concluido')
            ->whereNotNull('iniciado_em')
            ->whereNotNull('concluido_em')
            ->whereBetween('data_agendamento', [$dataIni, $dataFim])
            ->get();

        $porUnidade = $concluidas->groupBy(fn (OrdemServico $os) => $os->unidade->nome)
            ->map(function ($grupo, $nomeUnidade) {
                $minutos = $grupo->map(function (OrdemServico $os) {
                    // abs() porque o Carbon 3 retorna diff sinalizada por padrão.
                    return abs(Carbon::parse($os->concluido_em)
                        ->diffInMinutes(Carbon::parse($os->iniciado_em)));
                });

                return [
                    'unidade'        => $nomeUnidade,
                    'total_os'       => $grupo->count(),
                    'tempo_medio_min' => $minutos->count() ? round($minutos->avg()) : 0,
                ];
            })
            ->values();

        return view('relatorios.tempo-medio', [
            'porUnidade' => $porUnidade,
            'data_ini'   => $dataIni,
            'data_fim'   => $dataFim,
        ]);
    }
}
