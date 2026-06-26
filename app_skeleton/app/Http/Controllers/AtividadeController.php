<?php

namespace App\Http\Controllers;

use App\Models\Atividade;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AtividadeController extends Controller
{
    public function index()
    {
        return view('atividades.index', [
            'lista' => Atividade::withCount('ordensServico')->orderBy('nome')->get(),
        ]);
    }

    public function show(Atividade $atv)
    {
        $atv->load(['ordensServico' => fn ($q) => $q->orderByDesc('data_agendamento')->limit(10)]);
        $atv->loadCount('ordensServico');

        $totais = [
            'total'      => $atv->ordensServico()->count(),
            'concluidas' => $atv->ordensServico()->where('status', 'concluido')->count(),
            'pendentes'  => $atv->ordensServico()->whereIn('status', ['nao_iniciado', 'iniciado'])->count(),
            'nao_exec'   => $atv->ordensServico()->where('status', 'nao_executado')->count(),
        ];

        $ultimas = $atv->ordensServico()
            ->with(['profissional', 'unidade'])
            ->orderByDesc('data_agendamento')
            ->limit(5)
            ->get();

        return view('atividades.show', compact('atv', 'totais', 'ultimas'));
    }

    public function nova()
    {
        return view('atividades.form', ['atv' => null]);
    }

    public function criar(Request $request)
    {
        $dados = $this->validar($request);
        Atividade::create($dados);

        return redirect('/atividades')->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => 'Atividade cadastrada com sucesso.',
        ]);
    }

    public function editarForm(Atividade $atv)
    {
        return view('atividades.form', ['atv' => $atv]);
    }

    public function editar(Request $request, Atividade $atv)
    {
        $dados = $this->validar($request);
        $atv->update($dados);

        return redirect('/atividades')->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => 'Atividade atualizada com sucesso.',
        ]);
    }

    public function excluir(Atividade $atv)
    {
        $atv->update(['status' => 'inativa']);

        return back()->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => 'Atividade desativada.',
        ]);
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nome'   => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['ativa', 'inativa'])],
        ]);
    }
}
