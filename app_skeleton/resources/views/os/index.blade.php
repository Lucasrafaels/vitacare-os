@extends('layouts.app')

@section('content')
@php
    $statusMap = [
        'nao_iniciado'  => ['gray',  'Não iniciado'],
        'iniciado'      => ['amber', 'Em andamento'],
        'concluido'     => ['green', 'Concluído'],
        'nao_executado' => ['red',   'Não executado'],
    ];
@endphp
<div class="topbar">
    <span class="topbar-title">Ordens de Serviço</span>
    <div class="topbar-actions">
        @if (auth()->user()->ehGestor())
        <a href="/os/nova" class="btn btn-primary btn-sm">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nova OS
        </a>
        @endif
    </div>
</div>
<div class="page-body">
    <!-- Filtros -->
    <form method="GET" action="/os" class="filter-bar">
        <div class="form-group">
            <label>Data</label>
            <input type="date" name="data" value="{{ $filtros['data'] ?? '' }}">
        </div>
        @if (auth()->user()->ehGestor())
        <div class="form-group">
            <label>Profissional</label>
            <select name="profissional">
                <option value="">Todos</option>
                @foreach ($profissionais as $p)
                <option value="{{ $p->id }}" {{ ($filtros['profissional'] ?? '') == $p->id ? 'selected' : '' }}>
                    {{ $p->nome }}
                </option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="form-group">
            <label>Unidade</label>
            <select name="unidade">
                <option value="">Todas</option>
                @foreach ($unidades as $u)
                <option value="{{ $u->id }}" {{ ($filtros['unidade'] ?? '') == $u->id ? 'selected' : '' }}>
                    {{ $u->nome }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="">Todos</option>
                @foreach ($statusMap as $val => $info)
                <option value="{{ $val }}" {{ ($filtros['status'] ?? '') === $val ? 'selected' : '' }}>{{ $info[1] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="justify-content:flex-end;flex:none">
            <label>&nbsp;</label>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
                <a href="/os" class="btn btn-outline btn-sm">Limpar</a>
            </div>
        </div>
    </form>

    <div class="card">
        @if (empty($lista) || count($lista) === 0)
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            <div style="font-weight:600;color:#1e293b;margin-bottom:4px">Nenhuma OS encontrada</div>
            <div class="text-sm text-muted">Ajuste os filtros ou crie uma nova OS.</div>
        </div>
        @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        @if (auth()->user()->ehGestor())<th>Profissional</th>@endif
                        <th>Unidade</th>
                        <th>Atividade</th>
                        <th>Data / Hora</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($lista as $os)
                @php [$cls, $label] = $statusMap[$os['status']]; @endphp
                <tr>
                    <td><span class="os-codigo">{{ $os['codigo'] }}</span></td>
                    @if (auth()->user()->ehGestor())<td>{{ $os['profissional_nome'] }}</td>@endif
                    <td>{{ $os['unidade_nome'] }}</td>
                    <td>{{ $os['atividade_nome'] }}</td>
                    <td>
                        {{ $os['data_agendamento'] ? \Carbon\Carbon::parse($os['data_agendamento'])->format('d/m/Y') : '—' }}
                        {{ $os['hora_agendamento'] ? ' ' . \Carbon\Carbon::parse($os['hora_agendamento'])->format('H:i') : '' }}
                    </td>
                    <td><span class="badge badge-{{ $cls }}">{{ $label }}</span></td>
                    <td>
                        <div class="flex gap-2">
                            <a href="/os/{{ $os['id'] }}" class="btn btn-ghost btn-sm">Ver</a>
                            @if (auth()->user()->ehGestor())
                            <a href="/os/{{ $os['id'] }}/editar" class="btn btn-ghost btn-sm">Editar</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:12px 16px;font-size:12.5px;color:#64748b;border-top:1px solid #e2e8f0">
            {{ count($lista) }} resultado{{ count($lista) !== 1 ? 's' : '' }} encontrado{{ count($lista) !== 1 ? 's' : '' }}
        </div>
        @endif
    </div>
</div>
@endsection
