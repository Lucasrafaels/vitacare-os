@extends('layouts.app')

@section('content')
@php
    $statusMap = [
        'nao_iniciado'  => ['gray',  'Não iniciado'],
        'iniciado'      => ['amber', 'Em andamento'],
        'concluido'     => ['green', 'Concluído'],
        'nao_executado' => ['red',   'Não executado'],
    ];
    [$cls, $label] = $statusMap[$os['status']];
    $podeIniciar  = $os['status'] === 'nao_iniciado';
    $podeConcluir = $os['status'] === 'iniciado';
    $podeNaoExec  = in_array($os['status'], ['nao_iniciado', 'iniciado']);
    $u            = auth()->user();
    $minhaOs      = (int) $os['profissional_id'] === (int) $u->id;
    $ehGestor     = $u->ehGestor();
    $ehFacilitador = $u->ehFacilitador();
    $fichaPreenchida = !empty($os['tipo_intervencao'])
                    && !empty($os['resolucao'])
                    && !empty($os['contato_local']);
@endphp
<div class="topbar">
    <a href="/os" style="color:#64748b;font-size:13px">← Ordens de Serviço</a>
    <span class="topbar-title" style="margin-left:12px">{{ $os['codigo'] }}</span>
    <div class="topbar-actions">
        <a href="/os/{{ $os['id'] }}/pdf" target="_blank" class="btn btn-outline btn-sm">📄 Gerar PDF</a>
        @if ($ehGestor)
        <a href="/os/{{ $os['id'] }}/editar" class="btn btn-outline btn-sm">Editar</a>
        <form method="POST" action="/os/{{ $os['id'] }}/duplicar" style="display:inline" onsubmit="return confirm('Duplicar esta OS?')">
            @csrf
            <button type="submit" class="btn btn-outline btn-sm">Duplicar</button>
        </form>
        <form method="POST" action="/os/{{ $os['id'] }}/excluir" style="display:inline" onsubmit="return confirm('Excluir esta OS permanentemente?')">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
        </form>
        @endif
    </div>
</div>
<div class="page-body">
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">

        <!-- Detalhes principais -->
        <div>
            <div class="card">
                <div class="card-header">
                    <div>
                        <span class="card-title">{{ $os['codigo'] }}</span>
                        <span style="margin-left:10px"><span class="badge badge-{{ $cls }}">{{ $label }}</span></span>
                    </div>
                </div>
                <div class="card-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                        <div>
                            <div class="text-sm text-muted" style="margin-bottom:2px">Profissional</div>
                            <div style="font-weight:600">{{ $os['profissional_nome'] }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-muted" style="margin-bottom:2px">Unidade de Saúde</div>
                            <div style="font-weight:600">{{ $os['unidade_nome'] }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-muted" style="margin-bottom:2px">Atividade</div>
                            <div>{{ $os['atividade_nome'] }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-muted" style="margin-bottom:2px">Gestor responsável</div>
                            <div>{{ $os['gestor_nome'] }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-muted" style="margin-bottom:2px">Data agendada</div>
                            <div>{{ $os['data_agendamento'] ? \Carbon\Carbon::parse($os['data_agendamento'])->format('d/m/Y') : '—' }}
                            {{ $os['hora_agendamento'] ? ' às ' . \Carbon\Carbon::parse($os['hora_agendamento'])->format('H:i') : '' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-muted" style="margin-bottom:2px">Criada em</div>
                            <div>{{ \Carbon\Carbon::parse($os['criado_em'])->format('d/m/Y H:i') }}</div>
                        </div>
                        @if ($os['observacoes'])
                        <div style="grid-column:1/-1">
                            <div class="text-sm text-muted" style="margin-bottom:2px">Observações</div>
                            <div>{!! nl2br(e($os['observacoes'])) !!}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card" style="margin-top:16px">
                <div class="card-header"><span class="card-title">Ciclo de Vida</span></div>
                <div class="card-body">
                    @php
                        $steps = [
                            ['Criada', $os['criado_em'], 'blue'],
                            ['Iniciada', $os['iniciado_em'], 'amber'],
                            ['Concluída', $os['concluido_em'], 'green'],
                        ];
                    @endphp
                    <div style="display:flex;gap:0">
                    @foreach ($steps as [$nome, $dt, $c])
                    <div style="flex:1;text-align:center;position:relative">
                        <div style="width:10px;height:10px;border-radius:50%;background:{{ $dt ? "var(--$c)" : '#e2e8f0' }};margin:0 auto 6px"></div>
                        <div style="font-size:11.5px;font-weight:600;color:{{ $dt ? '#1e293b' : '#94a3b8' }}">{{ $nome }}</div>
                        <div style="font-size:11px;color:#64748b">{{ $dt ? \Carbon\Carbon::parse($dt)->format('d/m H:i') : '—' }}</div>
                    </div>
                    @endforeach
                    </div>

                    @if ($os['motivo_nao_execucao'])
                    <div style="margin-top:14px;background:#fee2e2;border-radius:6px;padding:10px;font-size:13px;color:#b91c1c">
                        <strong>Motivo de não execução:</strong> {{ $os['motivo_nao_execucao'] }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Ficha de atendimento -->
            @if ($os['tipo_intervencao'])
            <div class="card" style="margin-top:16px">
                <div class="card-header"><span class="card-title">Ficha de Atendimento</span></div>
                <div class="card-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                        <div>
                            <div class="text-sm text-muted" style="margin-bottom:2px">Tipo de intervenção</div>
                            <div>{{ $os['tipo_intervencao'] }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-muted" style="margin-bottom:2px">Contato local</div>
                            <div>{{ $os['contato_local'] }}</div>
                        </div>
                        <div style="grid-column:1/-1">
                            <div class="text-sm text-muted" style="margin-bottom:2px">Resolução</div>
                            <div>{!! nl2br(e($os['resolucao'])) !!}</div>
                        </div>
                        @if ($os['ficha_obs'])
                        <div style="grid-column:1/-1">
                            <div class="text-sm text-muted" style="margin-bottom:2px">Observações da ficha</div>
                            <div>{!! nl2br(e($os['ficha_obs'])) !!}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Ações -->
        <div>
            <div class="card">
                <div class="card-header"><span class="card-title">Ações</span></div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:10px">

                    @if ($podeIniciar && ($minhaOs || $ehGestor))
                    <form method="POST" action="/os/{{ $os['id'] }}/iniciar">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width:100%">▶ Iniciar atendimento</button>
                    </form>
                    @endif

                    {{-- RF12: facilitador preenche a ficha antes; só então aparece "Concluir". Gestor não conclui. --}}
                    @if ($podeConcluir && $minhaOs && $ehFacilitador)
                    <button type="button" class="btn btn-outline" style="width:100%"
                            onclick="document.getElementById('modal-ficha').style.display='flex'">
                        📝 {{ $fichaPreenchida ? 'Editar ficha de execução' : 'Preencher ficha de execução' }}
                    </button>
                        @if ($fichaPreenchida)
                        <form method="POST" action="/os/{{ $os['id'] }}/concluir" onsubmit="return confirm('Concluir este atendimento com a ficha salva?')">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="width:100%;background:#16a34a">✓ Concluir atendimento</button>
                        </form>
                        @else
                        <div style="font-size:12px;color:#92400e;background:#fef3c7;padding:8px 10px;border-radius:6px;text-align:center">
                            Preencha e salve a ficha de execução para liberar a conclusão.
                        </div>
                        @endif
                    @elseif ($podeConcluir && $ehGestor)
                        <div style="font-size:12px;color:#64748b;text-align:center;padding:4px 0">
                            Aguardando facilitador {{ $fichaPreenchida ? 'concluir' : 'preencher a ficha' }}.
                        </div>
                    @endif

                    @if ($podeNaoExec && ($minhaOs || $ehGestor))
                    <button type="button" class="btn btn-outline" style="width:100%;color:#ef4444;border-color:#ef4444"
                            onclick="document.getElementById('modal-nao-exec').style.display='flex'">
                        ✗ Não executada
                    </button>
                    @endif

                    @if (!$podeIniciar && !$podeConcluir && !$podeNaoExec)
                    <div style="text-align:center;color:#94a3b8;font-size:13px;padding:10px 0">
                        OS {{ strtolower($label) }}. Sem ações disponíveis.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ficha de Execução (RF12 — salva sem concluir) -->
<div id="modal-ficha" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:200;align-items:center;justify-content:center;padding:20px">
    <div style="background:#fff;border-radius:12px;padding:28px;width:100%;max-width:520px;box-shadow:0 25px 50px rgba(0,0,0,.25)">
        <h3 style="margin-bottom:6px;font-size:16px">Ficha de Atendimento</h3>
        <p style="margin-bottom:18px;font-size:12.5px;color:#64748b">Salve a ficha primeiro. O botão "Concluir" só será liberado depois.</p>
        <form method="POST" action="/os/{{ $os['id'] }}/ficha">
            @csrf
            <div class="form-group" style="margin-bottom:12px">
                <label>Tipo de Intervenção <span class="req">*</span></label>
                <input type="text" name="tipo_intervencao" required placeholder="Ex: Visita domiciliar, consulta…" value="{{ $os['tipo_intervencao'] ?? '' }}">
            </div>
            <div class="form-group" style="margin-bottom:12px">
                <label>Resolução <span class="req">*</span></label>
                <textarea name="resolucao" required rows="3" placeholder="Descreva o que foi realizado…">{{ $os['resolucao'] ?? '' }}</textarea>
            </div>
            <div class="form-group" style="margin-bottom:12px">
                <label>Contato Local <span class="req">*</span></label>
                <input type="text" name="contato_local" required placeholder="Nome e cargo do responsável na unidade" value="{{ $os['contato_local'] ?? '' }}">
            </div>
            <div class="form-group" style="margin-bottom:20px">
                <label>Observações</label>
                <textarea name="ficha_obs" rows="2" placeholder="Informações adicionais…">{{ $os['ficha_obs'] ?? '' }}</textarea>
            </div>
            <div class="flex gap-2 justify-between">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('modal-ficha').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn-primary">💾 Salvar ficha</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Não executada -->
<div id="modal-nao-exec" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:200;align-items:center;justify-content:center;padding:20px">
    <div style="background:#fff;border-radius:12px;padding:28px;width:100%;max-width:440px">
        <h3 style="margin-bottom:16px;font-size:16px">Motivo da Não Execução</h3>
        <form method="POST" action="/os/{{ $os['id'] }}/nao-executar">
            @csrf
            <div class="form-group" style="margin-bottom:20px">
                <label>Motivo <span class="req">*</span></label>
                <textarea name="motivo" required rows="3" placeholder="Explique o motivo pelo qual o atendimento não foi realizado…"></textarea>
            </div>
            <div class="flex gap-2 justify-between">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('modal-nao-exec').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn-danger">Confirmar</button>
            </div>
        </form>
    </div>
</div>
@endsection
