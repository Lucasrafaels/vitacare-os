<?php

namespace App\Http\Controllers;

use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnidadeController extends Controller
{
    public function index()
    {
        return view('unidades.index', [
            'lista' => Unidade::orderBy('nome')->get(),
        ]);
    }

    public function show(Unidade $uni)
    {
        $uni->load('ordensServico');

        $totais = [
            'total'      => $uni->ordensServico->count(),
            'concluidas' => $uni->ordensServico->where('status', 'concluido')->count(),
            'pendentes'  => $uni->ordensServico->whereIn('status', ['nao_iniciado', 'iniciado'])->count(),
            'nao_exec'   => $uni->ordensServico->where('status', 'nao_executado')->count(),
        ];

        $ultimas = $uni->ordensServico()
            ->with(['profissional', 'atividade'])
            ->orderByDesc('data_agendamento')
            ->limit(5)
            ->get();

        return view('unidades.show', compact('uni', 'totais', 'ultimas'));
    }

    public function nova()
    {
        return view('unidades.form', ['uni' => null]);
    }

    public function criar(Request $request)
    {
        $dados = $request->validate([
            'nome'     => ['required', 'string', 'max:255'],
            'cidade'   => ['required', 'string', 'max:255'],
            'endereco' => ['nullable', 'string'],
            'status'   => ['required', Rule::in(['ativa', 'inativa'])],
        ]);

        Unidade::create($dados);

        return redirect('/unidades')->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => 'Unidade cadastrada com sucesso.',
        ]);
    }

    public function editarForm(Unidade $uni)
    {
        return view('unidades.form', ['uni' => $uni]);
    }

    public function editar(Request $request, Unidade $uni)
    {
        $dados = $request->validate([
            'nome'     => ['required', 'string', 'max:255'],
            'cidade'   => ['required', 'string', 'max:255'],
            'endereco' => ['nullable', 'string'],
            'status'   => ['required', Rule::in(['ativa', 'inativa'])],
        ]);

        $uni->update($dados);

        return redirect('/unidades')->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => 'Unidade atualizada com sucesso.',
        ]);
    }

    public function excluir(Unidade $uni)
    {
        $uni->update(['status' => 'inativa']);

        return back()->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => 'Unidade desativada.',
        ]);
    }
}
