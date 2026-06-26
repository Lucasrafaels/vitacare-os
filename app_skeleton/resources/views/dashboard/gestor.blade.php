@extends('layouts.app')

@section('content')
<div class="topbar">
    <span class="topbar-title">Dashboard · Plano de Trabalho</span>
    <div class="topbar-actions">
        <span class="text-sm text-muted">{{ date('d/m/Y') }}</span>
        <a href="/os/nova" class="btn btn-primary btn-sm">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nova OS
        </a>
    </div>
</div>
<div class="page-body">
    <!-- Totais do dia -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $totais['total'] }}</div>
            <div class="stat-label">Total hoje</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" style="color:#64748b">{{ $totais['nao_iniciados'] }}</div>
            <div class="stat-label">Não iniciados</div>
        </div>
        <div class="stat-card amber">
            <div class="stat-value">{{ $totais['em_andamento'] }}</div>
            <div class="stat-label">Em andamento</div>
        </div>
        <div class="stat-card green">
            <div class="stat-value">{{ $totais['concluidos'] }}</div>
            <div class="stat-label">Concluídos</div>
        </div>
        <div class="stat-card red">
            <div class="stat-value">{{ $totais['nao_executados'] }}</div>
            <div class="stat-label">Não executados</div>
        </div>
    </div>

    <!-- Cards por profissional -->
    <div style="font-size:13px;font-weight:600;color:#475569;margin-bottom:12px;text-transform:uppercase;letter-spacing:.05em">
        Facilitadores em campo hoje
    </div>
    <div class="prof-cards">
        @foreach ($cards_profissionais as $p)
        @php $inicial = strtoupper(substr($p['nome'], 0, 1)); @endphp
        <div class="prof-card">
            <div class="prof-card-header">
                <div class="prof-card-avatar">{{ $inicial }}</div>
                <div>
                    <div style="font-weight:600;font-size:13.5px">{{ $p['nome'] }}</div>
                    @if ($p['total'] == 0)
                    <div style="font-size:11.5px;color:#94a3b8">Sem OS agendadas hoje</div>
                    @else
                    <div style="font-size:11.5px;color:#64748b">{{ $p['total'] }} OS no dia</div>
                    @endif
                </div>
                <a href="/os?profissional={{ $p['id'] }}&data={{ date('Y-m-d') }}" style="margin-left:auto;font-size:11.5px;color:#0d9488">ver →</a>
            </div>
            @if ($p['total'] > 0)
            <div class="prof-mini-stats">
                <div class="prof-mini-stat">
                    <span class="n" style="color:#64748b">{{ $p['nao_iniciados'] }}</span>
                    <span class="l">Pendente</span>
                </div>
                <div class="prof-mini-stat">
                    <span class="n" style="color:#d97706">{{ $p['em_andamento'] }}</span>
                    <span class="l">Andamento</span>
                </div>
                <div class="prof-mini-stat">
                    <span class="n" style="color:#16a34a">{{ $p['concluidos'] }}</span>
                    <span class="l">Concluído</span>
                </div>
                <div class="prof-mini-stat">
                    <span class="n" style="color:#ef4444">{{ $p['nao_executados'] }}</span>
                    <span class="l">Não exec.</span>
                </div>
            </div>
            @else
            <div style="text-align:center;padding:10px 0;color:#94a3b8;font-size:12px">Nenhuma OS para hoje</div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Quick links -->
    <div class="action-row" style="margin-top:24px">
        <a href="/os" class="btn btn-outline">Ver todas as OS</a>
        <a href="/relatorios/os-profissional" class="btn btn-outline">Relatório por profissional</a>
        <a href="/relatorios/tempo-medio" class="btn btn-outline">Tempo médio por unidade</a>
    </div>
</div>
@endsection
