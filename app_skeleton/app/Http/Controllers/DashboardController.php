<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\Profissional;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        if ($usuario->ehGestor()) {
            return $this->dashboardGestor();
        }

        return $this->dashboardFacilitador($usuario);
    }

    private function dashboardGestor()
    {
        $hoje = Carbon::today();

        $osHoje = OrdemServico::doDia($hoje)->get();

        $totais = [
            'total'           => $osHoje->count(),
            'nao_iniciados'   => $osHoje->where('status', 'nao_iniciado')->count(),
            'em_andamento'    => $osHoje->where('status', 'iniciado')->count(),
            'concluidos'      => $osHoje->where('status', 'concluido')->count(),
            'nao_executados'  => $osHoje->where('status', 'nao_executado')->count(),
        ];

        $facilitadores = Profissional::where('perfil', 'facilitador')
            ->where('status', 'ativo')
            ->orderBy('nome')
            ->get();

        $cardsProfissionais = $facilitadores->map(function (Profissional $p) use ($osHoje) {
            $minhasOs = $osHoje->where('profissional_id', $p->id);

            return [
                'id'              => $p->id,
                'nome'            => $p->nome,
                'total'           => $minhasOs->count(),
                'nao_iniciados'   => $minhasOs->where('status', 'nao_iniciado')->count(),
                'em_andamento'    => $minhasOs->where('status', 'iniciado')->count(),
                'concluidos'      => $minhasOs->where('status', 'concluido')->count(),
                'nao_executados'  => $minhasOs->where('status', 'nao_executado')->count(),
            ];
        });

        return view('dashboard.gestor', [
            'totais'             => $totais,
            'cards_profissionais' => $cardsProfissionais,
        ]);
    }

    private function dashboardFacilitador(Profissional $usuario)
    {
        $hoje = Carbon::today();

        $minhasOs = OrdemServico::with(['unidade', 'atividade'])
            ->doProfissional($usuario->id)
            ->doDia($hoje)
            ->orderBy('hora_agendamento')
            ->get()
            ->map(fn (OrdemServico $os) => [
                'id'               => $os->id,
                'codigo'           => $os->codigo,
                'unidade'          => $os->unidade->nome,
                'atividade'        => $os->atividade->nome,
                'observacoes'      => $os->observacoes,
                'hora_agendamento' => $os->hora_agendamento,
                'status'           => $os->status,
            ]);

        return view('dashboard.facilitador', [
            'minhas_os' => $minhasOs,
        ]);
    }
}
