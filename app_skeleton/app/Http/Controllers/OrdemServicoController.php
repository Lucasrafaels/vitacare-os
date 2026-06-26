<?php

namespace App\Http\Controllers;

use App\Models\Atividade;
use App\Models\OrdemServico;
use App\Models\Profissional;
use App\Models\Unidade;
use Illuminate\Http\Request;

class OrdemServicoController extends Controller
{
    /* ---------------------------------- Agenda ---------------------------------- */

    public function agenda(Request $request)
    {
        // Mostra OS dos próximos 7 dias a partir de hoje, agrupadas por dia
        $inicio = now()->startOfDay();
        $fim    = now()->addDays(6)->endOfDay();

        $usuario = $request->user();

        $query = OrdemServico::with(['profissional', 'unidade', 'atividade'])
            ->whereBetween('data_agendamento', [$inicio->toDateString(), $fim->toDateString()])
            ->orderBy('data_agendamento')
            ->orderBy('hora_agendamento');

        if ($usuario->ehFacilitador()) {
            $query->where('profissional_id', $usuario->id);
        }

        $porDia = $query->get()->groupBy(fn ($os) => $os->data_agendamento->format('Y-m-d'));

        // Garantir que todos os 7 dias apareçam mesmo sem OS
        $dias = collect();
        for ($i = 0; $i < 7; $i++) {
            $data = now()->addDays($i)->toDateString();
            $dias[$data] = $porDia[$data] ?? collect();
        }

        return view('os.agenda', [
            'dias'          => $dias,
            'profissionais' => Profissional::where('perfil', 'facilitador')->orderBy('nome')->get(),
        ]);
    }

    /* --------------------------------- Listagem --------------------------------- */

    public function index(Request $request)
    {
        $usuario = $request->user();

        $query = OrdemServico::with(['profissional', 'unidade', 'atividade']);

        // Facilitador só vê as próprias OS — requisito obrigatório.
        if ($usuario->ehFacilitador()) {
            $query->where('profissional_id', $usuario->id);
        }

        $filtros = $request->only(['data', 'profissional', 'unidade', 'status']);

        if (! empty($filtros['data'])) {
            $query->whereDate('data_agendamento', $filtros['data']);
        }

        if ($usuario->ehGestor() && ! empty($filtros['profissional'])) {
            $query->where('profissional_id', $filtros['profissional']);
        }

        if (! empty($filtros['unidade'])) {
            $query->where('unidade_id', $filtros['unidade']);
        }

        if (! empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        }

        $lista = $query->orderByDesc('data_agendamento')
            ->orderByDesc('id')
            ->get()
            ->map(fn (OrdemServico $os) => [
                'id'                => $os->id,
                'codigo'            => $os->codigo,
                'profissional_nome' => $os->profissional->nome,
                'unidade_nome'      => $os->unidade->nome,
                'atividade_nome'    => $os->atividade->nome,
                'data_agendamento'  => $os->data_agendamento,
                'hora_agendamento'  => $os->hora_agendamento,
                'status'            => $os->status,
            ]);

        return view('os.index', [
            'lista'         => $lista,
            'filtros'       => $filtros,
            'profissionais' => Profissional::where('perfil', 'facilitador')->orderBy('nome')->get(),
            'unidades'      => Unidade::ativas()->orderBy('nome')->get(),
        ]);
    }

    /* ---------------------------------- Detalhe ---------------------------------- */

    public function show(Request $request, OrdemServico $os)
    {
        $this->autorizarAcesso($request, $os);

        $os->load(['profissional', 'unidade', 'atividade', 'gestor']);

        return view('os.show', [
            'os' => [
                'id'                  => $os->id,
                'codigo'              => $os->codigo,
                'profissional_id'     => $os->profissional_id,
                'profissional_nome'   => $os->profissional->nome,
                'unidade_nome'        => $os->unidade->nome,
                'atividade_nome'      => $os->atividade->nome,
                'gestor_nome'         => $os->gestor->nome ?? '—',
                'data_agendamento'    => $os->data_agendamento,
                'hora_agendamento'    => $os->hora_agendamento,
                'observacoes'         => $os->observacoes,
                'status'              => $os->status,
                'criado_em'           => $os->created_at,
                'iniciado_em'         => $os->iniciado_em,
                'concluido_em'        => $os->concluido_em,
                'tipo_intervencao'    => $os->tipo_intervencao,
                'resolucao'           => $os->resolucao,
                'contato_local'       => $os->contato_local,
                'ficha_obs'           => $os->ficha_obs,
                'motivo_nao_execucao' => $os->motivo_nao_execucao,
            ],
        ]);
    }

    /* ------------------------------- Criar / Editar ------------------------------ */

    public function nova(Request $request)
    {
        $this->autorizarGestor($request);

        return view('os.form', [
            'os'            => null,
            'profissionais' => Profissional::where('perfil', 'facilitador')->where('status', 'ativo')->orderBy('nome')->get(),
            'unidades'      => Unidade::ativas()->orderBy('nome')->get(),
            'atividades'    => Atividade::ativas()->orderBy('nome')->get(),
        ]);
    }

    public function criar(Request $request)
    {
        $this->autorizarGestor($request);

        $dados = $this->validarFormulario($request);

        OrdemServico::create([
            ...$dados,
            'codigo'    => OrdemServico::gerarCodigo(),
            'gestor_id' => $request->user()->id,
            'status'    => 'nao_iniciado',
        ]);

        return redirect('/os')->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => 'Ordem de serviço criada com sucesso.',
        ]);
    }

    public function editarForm(Request $request, OrdemServico $os)
    {
        $this->autorizarGestor($request);

        return view('os.form', [
            'os'            => $os,
            'profissionais' => Profissional::where('perfil', 'facilitador')->where('status', 'ativo')->orderBy('nome')->get(),
            'unidades'      => Unidade::ativas()->orderBy('nome')->get(),
            'atividades'    => Atividade::ativas()->orderBy('nome')->get(),
        ]);
    }

    public function editar(Request $request, OrdemServico $os)
    {
        $this->autorizarGestor($request);

        $dados = $this->validarFormulario($request);

        $os->update($dados);

        return redirect("/os/{$os->id}")->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => 'OS atualizada com sucesso.',
        ]);
    }

    public function excluir(Request $request, OrdemServico $os)
    {
        $this->autorizarGestor($request);

        $os->delete();

        return redirect('/os')->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => 'OS excluída com sucesso.',
        ]);
    }

    public function duplicar(Request $request, OrdemServico $os)
    {
        $this->autorizarGestor($request);

        $nova = $os->duplicar();

        return redirect("/os/{$nova->id}")->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => "OS duplicada com sucesso como {$nova->codigo}.",
        ]);
    }

    /* ------------------------------- Ciclo de vida -------------------------------- */

    public function iniciar(Request $request, OrdemServico $os)
    {
        $this->autorizarAcesso($request, $os);

        try {
            $os->iniciar();
        } catch (\RuntimeException $e) {
            return back()->with('flash', ['tipo' => 'erro', 'msg' => $e->getMessage()]);
        }

        return back()->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => 'Atendimento iniciado.',
        ]);
    }

    public function concluir(Request $request, OrdemServico $os)
    {
        $this->autorizarAcesso($request, $os);

        // RF12: a ficha precisa ter sido salva ANTES da conclusão.
        if (empty($os->tipo_intervencao) || empty($os->resolucao) || empty($os->contato_local)) {
            return back()->with('flash', [
                'tipo' => 'erro',
                'msg'  => 'Preencha e salve a ficha de atendimento antes de concluir.',
            ]);
        }

        // Apenas o facilitador responsável conclui (gestor acompanha).
        if (! $request->user()->ehFacilitador() || (int) $os->profissional_id !== (int) $request->user()->id) {
            return back()->with('flash', [
                'tipo' => 'erro',
                'msg'  => 'Somente o facilitador responsável pode concluir a OS.',
            ]);
        }

        $ficha = [
            'tipo_intervencao' => $os->tipo_intervencao,
            'resolucao'        => $os->resolucao,
            'contato_local'    => $os->contato_local,
            'ficha_obs'        => $os->ficha_obs,
        ];

        try {
            $os->concluir($ficha);
        } catch (\RuntimeException $e) {
            return back()->with('flash', ['tipo' => 'erro', 'msg' => $e->getMessage()]);
        }

        return redirect("/os/{$os->id}")->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => 'Atendimento concluído com sucesso.',
        ]);
    }

    /**
     * RF12: salva a ficha de execução sem concluir a OS.
     * O botão "Concluir" só aparece depois que esta ficha estiver salva.
     */
    public function salvarFicha(Request $request, OrdemServico $os)
    {
        $this->autorizarAcesso($request, $os);

        if ($os->status !== 'iniciado') {
            return back()->with('flash', [
                'tipo' => 'erro',
                'msg'  => 'A ficha só pode ser preenchida com a OS em andamento.',
            ]);
        }

        $ficha = $request->validate([
            'tipo_intervencao' => ['required', 'string', 'max:255'],
            'resolucao'        => ['required', 'string'],
            'contato_local'    => ['required', 'string', 'max:255'],
            'ficha_obs'        => ['nullable', 'string'],
        ]);

        $os->update($ficha);

        return redirect("/os/{$os->id}")->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => 'Ficha salva. Você já pode concluir o atendimento.',
        ]);
    }

    public function naoExecutar(Request $request, OrdemServico $os)
    {
        $this->autorizarAcesso($request, $os);

        // Motivo obrigatório — requisito obrigatório da planilha.
        $dados = $request->validate([
            'motivo' => ['required', 'string', 'min:5'],
        ]);

        try {
            $os->marcarNaoExecutada($dados['motivo']);
        } catch (\RuntimeException $e) {
            return back()->with('flash', ['tipo' => 'erro', 'msg' => $e->getMessage()]);
        }

        return redirect("/os/{$os->id}")->with('flash', [
            'tipo' => 'sucesso',
            'msg'  => 'OS marcada como não executada.',
        ]);
    }

    /* ----------------------------------- PDF ------------------------------------- */

    public function pdf(Request $request, OrdemServico $os)
    {
        $this->autorizarAcesso($request, $os);

        $os->load(['profissional', 'unidade', 'atividade', 'gestor']);

        $dados = [
            'os' => [
                'codigo'              => $os->codigo,
                'profissional_nome'   => $os->profissional->nome,
                'unidade_nome'        => $os->unidade->nome,
                'atividade_nome'      => $os->atividade->nome,
                'gestor_nome'         => $os->gestor->nome ?? '—',
                'data_agendamento'    => $os->data_agendamento,
                'hora_agendamento'    => $os->hora_agendamento,
                'observacoes'         => $os->observacoes,
                'status'              => $os->status,
                'iniciado_em'         => $os->iniciado_em,
                'concluido_em'        => $os->concluido_em,
                'tipo_intervencao'    => $os->tipo_intervencao,
                'resolucao'           => $os->resolucao,
                'contato_local'       => $os->contato_local,
                'ficha_obs'           => $os->ficha_obs,
                'motivo_nao_execucao' => $os->motivo_nao_execucao,
            ],
        ];

        // Se DomPDF estiver instalado, gera arquivo .pdf para download.
        // Caso contrário, exibe a view HTML imprimível (fallback).
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('os.pdf', $dados + ['pdfMode' => true]);
            return $pdf->download("{$os->codigo}.pdf");
        }

        return view('os.pdf', $dados);
    }

    /* --------------------------------- Auxiliares --------------------------------- */

    private function validarFormulario(Request $request): array
    {
        return $request->validate([
            'profissional_id'   => [
                'required',
                // só facilitador ativo pode receber uma OS
                \Illuminate\Validation\Rule::exists('profissionais', 'id')
                    ->where(fn ($q) => $q->where('perfil', 'facilitador')->where('status', 'ativo')),
            ],
            'unidade_id'        => [
                'required',
                \Illuminate\Validation\Rule::exists('unidades', 'id')
                    ->where(fn ($q) => $q->where('status', 'ativa')),
            ],
            'atividade_id'      => [
                'required',
                \Illuminate\Validation\Rule::exists('atividades', 'id')
                    ->where(fn ($q) => $q->where('status', 'ativa')),
            ],
            'data_agendamento'  => ['required', 'date'],
            'hora_agendamento'  => ['required', 'date_format:H:i'],
            'observacoes'       => ['nullable', 'string', 'max:1000'],
        ], [
            'profissional_id.exists' => 'Selecione um facilitador ativo.',
            'unidade_id.exists'      => 'Selecione uma unidade ativa.',
            'atividade_id.exists'    => 'Selecione uma atividade ativa.',
            'data_agendamento.required' => 'Informe a data do agendamento.',
            'hora_agendamento.required' => 'Informe o horário do agendamento.',
            'hora_agendamento.date_format' => 'Horário inválido (use HH:MM).',
        ]);
    }

    private function autorizarGestor(Request $request): void
    {
        if (! $request->user()->ehGestor()) {
            abort(403, 'Apenas gestores podem realizar esta ação.');
        }
    }

    private function autorizarAcesso(Request $request, OrdemServico $os): void
    {
        $usuario = $request->user();

        // Facilitador só pode ver/agir na própria OS. Gestor vê tudo.
        if ($usuario->ehFacilitador() && (int) $os->profissional_id !== (int) $usuario->id) {
            abort(403, 'Você não tem permissão para acessar esta OS.');
        }
    }
}
