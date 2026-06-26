<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>OS {{ $os['codigo'] }} · VitaCare</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1e293b; padding: 30px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #0d9488; padding-bottom: 14px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; color: #0d9488; }
        .header h1 em { font-style: normal; }
        .header p { font-size: 11px; color: #64748b; margin-top: 4px; }
        .codigo { font-size: 16px; font-weight: 700; font-family: monospace; color: #1e293b; }
        .status { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .s-nao_iniciado  { background: #f1f5f9; color: #64748b; }
        .s-iniciado      { background: #fef3c7; color: #92400e; }
        .s-concluido     { background: #dcfce7; color: #15803d; }
        .s-nao_executado { background: #fee2e2; color: #b91c1c; }
        .section { margin-bottom: 20px; }
        .section h2 { font-size: 13px; font-weight: 700; color: #0d9488; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; margin-bottom: 12px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .field label { font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em; display: block; margin-bottom: 2px; }
        .field span { font-size: 12px; color: #1e293b; }
        .ficha-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px; }
        .motivo-box { background: #fee2e2; border: 1px solid #fecaca; border-radius: 6px; padding: 10px; color: #b91c1c; }
        .footer { margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 10px; font-size: 10px; color: #94a3b8; display: flex; justify-content: space-between; }
        @media print {
            body { padding: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

@php
    $statusLabel = [
        'nao_iniciado'=>'Não iniciado','iniciado'=>'Em andamento',
        'concluido'=>'Concluído','nao_executado'=>'Não executado'
    ][$os['status']];
@endphp

<div class="header">
    <div>
        <h1>Vita<em>Care</em> OS</h1>
        <p>Sistema de Gestão de Ordens de Serviço</p>
    </div>
    <div style="text-align:right">
        <div class="codigo">{{ $os['codigo'] }}</div>
        <div style="margin-top:6px"><span class="status s-{{ $os['status'] }}">{{ $statusLabel }}</span></div>
    </div>
</div>

<div class="section">
    <h2>Dados da Ordem de Serviço</h2>
    <div class="grid">
        <div class="field"><label>Profissional</label><span>{{ $os['profissional_nome'] }}</span></div>
        <div class="field"><label>Unidade de Saúde</label><span>{{ $os['unidade_nome'] }}</span></div>
        <div class="field"><label>Atividade</label><span>{{ $os['atividade_nome'] }}</span></div>
        <div class="field"><label>Gestor Responsável</label><span>{{ $os['gestor_nome'] }}</span></div>
        <div class="field"><label>Data Agendada</label><span>{{ $os['data_agendamento'] ? \Carbon\Carbon::parse($os['data_agendamento'])->format('d/m/Y') : '—' }}</span></div>
        <div class="field"><label>Hora Agendada</label><span>{{ $os['hora_agendamento'] ? \Carbon\Carbon::parse($os['hora_agendamento'])->format('H:i') : '—' }}</span></div>
        <div class="field"><label>Iniciada em</label><span>{{ $os['iniciado_em'] ? \Carbon\Carbon::parse($os['iniciado_em'])->format('d/m/Y H:i') : '—' }}</span></div>
        <div class="field"><label>Concluída em</label><span>{{ $os['concluido_em'] ? \Carbon\Carbon::parse($os['concluido_em'])->format('d/m/Y H:i') : '—' }}</span></div>
        @if ($os['observacoes'])
        <div class="field" style="grid-column:1/-1"><label>Observações</label><span>{!! nl2br(e($os['observacoes'])) !!}</span></div>
        @endif
    </div>
</div>

@if ($os['tipo_intervencao'])
<div class="section">
    <h2>Ficha de Atendimento</h2>
    <div class="ficha-box">
        <div class="grid">
            <div class="field"><label>Tipo de Intervenção</label><span>{{ $os['tipo_intervencao'] }}</span></div>
            <div class="field"><label>Contato Local</label><span>{{ $os['contato_local'] }}</span></div>
            <div class="field" style="grid-column:1/-1"><label>Resolução</label><span>{!! nl2br(e($os['resolucao'])) !!}</span></div>
            @if ($os['ficha_obs'])
            <div class="field" style="grid-column:1/-1"><label>Observações</label><span>{!! nl2br(e($os['ficha_obs'])) !!}</span></div>
            @endif
        </div>
    </div>
</div>
@endif

@if ($os['motivo_nao_execucao'])
<div class="section">
    <h2>Motivo de Não Execução</h2>
    <div class="motivo-box">{!! nl2br(e($os['motivo_nao_execucao'])) !!}</div>
</div>
@endif

<!-- Assinaturas -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-top:40px">
    <div>
        <div style="border-top:1px solid #1e293b;padding-top:6px;font-size:11px;text-align:center">
            {{ $os['profissional_nome'] }}<br><span style="color:#64748b">Facilitador</span>
        </div>
    </div>
    <div>
        <div style="border-top:1px solid #1e293b;padding-top:6px;font-size:11px;text-align:center">
            {{ $os['unidade_nome'] }}<br><span style="color:#64748b">Unidade de Saúde</span>
        </div>
    </div>
</div>

<div class="footer">
    <span>VitaCare OS · Sistema de Gestão de Ordens de Serviço</span>
    <span>Gerado em {{ date('d/m/Y H:i') }}</span>
</div>

<div class="no-print" style="text-align:center;margin-top:24px">
    <button onclick="window.print()" style="padding:10px 24px;background:#0d9488;color:#fff;border:none;border-radius:8px;font-size:14px;cursor:pointer;font-family:inherit">
        🖨️ Imprimir / Salvar PDF
    </button>
    <a href="javascript:history.back()" style="margin-left:12px;font-size:13px;color:#0d9488">← Voltar</a>
</div>
</body>
</html>
