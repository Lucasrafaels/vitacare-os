@extends('layouts.app')

@section('content')
@php
    $statusMap = ['nao_iniciado'=>['gray','Não iniciado'],'iniciado'=>['amber','Em andamento'],'concluido'=>['green','Concluído'],'nao_executado'=>['red','Não executado']];
@endphp
<div class="topbar">
    <span class="topbar-title">Relatório · OS por Profissional</span>
    <div class="topbar-actions">
        <a href="/relatorios/tempo-medio" class="btn btn-outline btn-sm">Tempo médio →</a>
    </div>
</div>
<div class="page-body">
    <!-- Filtro -->
    <form method="GET" action="/relatorios/os-profissional" class="filter-bar">
        <div class="form-group">
            <label>Data inicial</label>
            <input type="date" name="data_ini" value="{{ $data_ini }}">
        </div>
        <div class="form-group">
            <label>Data final</label>
            <input type="date" name="data_fim" value="{{ $data_fim }}">
        </div>
        <div class="form-group">
            <label>Profissional</label>
            <select name="profissional">
                <option value="">Todos</option>
                @foreach ($profissionais as $p)
                <option value="{{ $p->id }}" {{ $prof_id == $p->id ? 'selected' : '' }}>{{ $p->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="justify-content:flex-end;flex:none">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
        </div>
    </form>
    @if ($lista->isEmpty())
    <div class="card"><div class="empty-state">Nenhuma OS no período selecionado.</div></div>
    @else
    @php $grouped = $lista->groupBy('profissional_nome'); @endphp
    @foreach ($grouped as $nome => $items)
    @php
        $total = $items->count();
        $conc  = $items->where('status', 'concluido')->count();
        $naoex = $items->where('status', 'nao_executado')->count();
    @endphp
    <div class="card" style="margin-bottom:20px">
        <div class="card-header">
            <span class="card-title">{{ $nome }}</span>
            <div class="flex gap-2">
                <span class="badge badge-gray">{{ $total }} OS</span>
                <span class="badge badge-green">{{ $conc }} concluídas</span>
                @if ($naoex)<span class="badge badge-red">{{ $naoex }} não exec.</span>@endif
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Código</th><th>Unidade</th><th>Atividade</th><th>Data</th><th>Início</th><th>Fim</th><th>Status</th></tr></thead>
                <tbody>
                @foreach ($items as $os)
                @php [$cls, $lbl] = $statusMap[$os['status']]; @endphp
                <tr>
                    <td><a href="/os/{{ $os['id'] }}" class="os-codigo">{{ $os['codigo'] }}</a></td>
                    <td>{{ $os['unidade_nome'] }}</td>
                    <td>{{ $os['atividade_nome'] }}</td>
                    <td>{{ $os['data_agendamento'] ? \Carbon\Carbon::parse($os['data_agendamento'])->format('d/m/Y') : '—' }}</td>
                    <td>{{ $os['iniciado_em'] ? \Carbon\Carbon::parse($os['iniciado_em'])->format('H:i') : '—' }}</td>
                    <td>{{ $os['concluido_em'] ? \Carbon\Carbon::parse($os['concluido_em'])->format('H:i') : '—' }}</td>
                    <td><span class="badge badge-{{ $cls }}">{{ $lbl }}</span></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection
