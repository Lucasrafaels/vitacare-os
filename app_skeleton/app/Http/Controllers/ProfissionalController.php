<?php

namespace App\Http\Controllers;

use App\Models\Profissional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfissionalController extends Controller
{
    public function index()
    {
        return view('profissionais.index', [
            'lista' => Profissional::orderBy('nome')->get(),
        ]);
    }

    public function show(Profissional $prof)
    {
        $prof->load('ordensServico');

        $totais = [
            'total'        => $prof->ordensServico->count(),
            'concluidas'   => $prof->ordensServico->where('status', 'concluido')->count(),
            'pendentes'    => $prof->ordensServico->whereIn('status', ['nao_iniciado', 'iniciado'])->count(),
            'nao_exec'     => $prof->ordensServico->where('status', 'nao_executado')->count(),
        ];

        $ultimas = $prof->ordensServico()
            ->with(['unidade', 'atividade'])
            ->orderByDesc('data_agendamento')
            ->limit(5)
            ->get();

        return view('profissionais.show', compact('prof', 'totais', 'ultimas'));
    }

    public function novo()
    {
        return view('profissionais.form', ['prof' => null]);
    }

    public function criar(Request $request)
    {
        $dados = $request->validate([
            'nome'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'email', 'unique:profissionais,email'],
            'cargo'  => ['nullable', 'string', 'max:255'],
            'perfil' => ['required', Rule::in(['gestor', 'facilitador'])],
            'status' => ['required', Rule::in(['ativo', 'inativo'])],
            'senha'  => ['required', 'string', 'min:6'],
        ]);

        Profissional::create([
            ...$dados,
            'senha' => Hash::make($dados['senha']),
        ]);

        return redirect('/profissionais')->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => 'Profissional cadastrado com sucesso.',
        ]);
    }

    public function editarForm(Profissional $prof)
    {
        return view('profissionais.form', ['prof' => $prof]);
    }

    public function editar(Request $request, Profissional $prof)
    {
        $dados = $request->validate([
            'nome'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'email', Rule::unique('profissionais', 'email')->ignore($prof->id)],
            'cargo'  => ['nullable', 'string', 'max:255'],
            'perfil' => ['required', Rule::in(['gestor', 'facilitador'])],
            'status' => ['required', Rule::in(['ativo', 'inativo'])],
            'senha'  => ['nullable', 'string', 'min:6'],
        ]);

        if (! empty($dados['senha'])) {
            $dados['senha'] = Hash::make($dados['senha']);
        } else {
            unset($dados['senha']);
        }

        $prof->update($dados);

        return redirect('/profissionais')->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => 'Profissional atualizado com sucesso.',
        ]);
    }

    public function excluir(Request $request, Profissional $prof)
    {
        if ($prof->id === $request->user()->id) {
            return back()->with('flash', [
                'tipo' => 'erro',
                'msg'  => 'Você não pode desativar a própria conta.',
            ]);
        }

        $prof->update(['status' => 'inativo']);

        return back()->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => 'Profissional desativado.',
        ]);
    }
}
