<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index()
    {
        return view('portal.index');
    }

    public function pesquisar(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $visitas = collect();

        if ($q !== '') {
            $visitas = OrdemServico::query()
                ->with(['profissional', 'unidade', 'atividade'])
                ->where(function ($query) use ($q) {
                    $query->where('codigo', 'like', "%{$q}%")
                        ->orWhereHas('unidade', fn ($u) => $u->where('nome', 'like', "%{$q}%"))
                        ->orWhereHas('profissional', fn ($p) => $p->where('nome', 'like', "%{$q}%"));
                })
                ->orderByDesc('data_agendamento')
                ->limit(50)
                ->get()
                ->map(function (OrdemServico $os) {
                    return [
                        'codigo'           => $os->codigo,
                        'profissional'     => $os->profissional->nome,
                        'unidade'          => $os->unidade->nome,
                        'atividade'        => $os->atividade->nome,
                        'data_agendamento' => $os->data_agendamento,
                        'status'           => $os->status,
                    ];
                });
        }

        return view('portal.index', [
            'q'       => $q,
            'visitas' => $visitas,
        ]);
    }
}
