@extends('layouts.app')

@section('content')
<div class="topbar">
    <span class="topbar-title">Minhas OS de Hoje</span>
    <div class="topbar-actions">
        <span class="text-sm text-muted">{{ date('d/m/Y') }}</span>
    </div>
</div>
<div class="page-body">
    @php
        $statusMap = [
            'nao_iniciado'  => ['gray',  'Não iniciado'],
            'iniciado'      => ['amber', 'Em andamento'],
            'concluido'     => ['green', 'Concluído'],
            'nao_executado' => ['red',   'Não executado'],
        ];
    @endphp
    @if (empty($minhas_os) || count($minhas_os) === 0)
    <div class="card">
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            <div style="font-weight:600;color:#1e293b;margin-bottom:4px">Nenhuma OS agendada para hoje</div>
            <div class="text-sm text-muted">Verifique com seu gestor.</div>
        </div>
    </div>
    @else
    <div style="margin-bottom:16px">
        @php
            $total = count($minhas_os);
            $pendentes = $minhas_os->where('status', 'nao_iniciado')->count();
            $andamento = $minhas_os->where('status', 'iniciado')->count();
            $concluidos = $minhas_os->where('status', 'concluido')->count();
        @endphp
        <div class="stats-grid">
            <div class="stat-card"><div class="stat-value">{{ $total }}</div><div class="stat-label">Total no dia</div></div>
            <div class="stat-card"><div class="stat-value" style="color:#64748b">{{ $pendentes }}</div><div class="stat-label">Pendentes</div></div>
            <div class="stat-card amber"><div class="stat-value">{{ $andamento }}</div><div class="stat-label">Em andamento</div></div>
            <div class="stat-card green"><div class="stat-value">{{ $concluidos }}</div><div class="stat-label">Concluídos</div></div>
        </div>
    </div>
    @foreach ($minhas_os as $os)
    @php [$cls, $label] = $statusMap[$os['status']]; @endphp
    <div class="os-card" style="margin-bottom:12px">
        <div class="os-card-header">
            <div>
                <span class="os-codigo">{{ $os['codigo'] }}</span>
                <span style="margin-left:8px"><span class="badge badge-{{ $cls }}">{{ $label }}</span></span>
            </div>
            @if ($os['hora_agendamento'])
            <span style="font-size:13px;color:#64748b;font-weight:500">{{ \Carbon\Carbon::parse($os['hora_agendamento'])->format('H:i') }}</span>
            @endif
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:13px;color:#475569;margin-bottom:12px">
            <div><strong>Unidade:</strong> {{ $os['unidade'] }}</div>
            <div><strong>Atividade:</strong> {{ $os['atividade'] }}</div>
            @if ($os['observacoes'])
            <div style="grid-column:1/-1"><strong>Obs:</strong> {{ $os['observacoes'] }}</div>
            @endif
        </div>
        <div class="flex gap-2">
            <a href="/os/{{ $os['id'] }}" class="btn btn-outline btn-sm">Ver detalhes</a>
            @if ($os['status'] === 'nao_iniciado')
            <form method="POST" action="/os/{{ $os['id'] }}/iniciar" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">Iniciar atendimento</button>
            </form>
            @endif
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection
