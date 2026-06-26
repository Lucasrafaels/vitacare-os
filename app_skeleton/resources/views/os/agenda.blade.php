@extends('layouts.app')

@section('content')
<div class="topbar">
    <span class="topbar-title">Agenda de Atendimentos</span>
    <div class="topbar-actions">
        @if (auth()->user()->ehGestor())
        <a href="/os/nova" class="btn btn-primary btn-sm">+ Nova OS</a>
        @endif
    </div>
</div>

<div class="page-body">
    <div style="margin-bottom:20px;background:#fff;border:1px solid var(--border);border-radius:var(--radius);padding:12px 16px;display:flex;align-items:center;gap:8px;font-size:13px;color:#475569">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Próximos 7 dias — {{ now()->format('d/m/Y') }} até {{ now()->addDays(6)->format('d/m/Y') }}
    </div>

    @foreach ($dias as $dataStr => $os_do_dia)
    @php
        $data   = \Carbon\Carbon::parse($dataStr);
        $hoje   = $data->isToday();
        $amanha = $data->isTomorrow();
        $label  = $hoje ? 'Hoje' : ($amanha ? 'Amanhã' : $data->isoFormat('dddd, D [de] MMMM'));
        $total  = $os_do_dia->count();
        $conc   = $os_do_dia->where('status', 'concluido')->count();
        $pend   = $os_do_dia->whereIn('status', ['nao_iniciado', 'iniciado'])->count();
    @endphp

    <div style="margin-bottom:16px">
        {{-- Cabeçalho do dia --}}
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
            <div style="width:8px;height:8px;border-radius:50%;background:{{ $hoje ? '#0d9488' : '#94a3b8' }};flex-shrink:0"></div>
            <span style="font-size:13px;font-weight:600;color:{{ $hoje ? '#0d9488' : '#475569' }};text-transform:capitalize">
                {{ $label }}
            </span>
            <span style="font-size:12px;color:#94a3b8">{{ $data->format('d/m/Y') }}</span>
            @if ($total > 0)
            <span style="background:#f1f5f9;color:#64748b;font-size:11px;padding:1px 8px;border-radius:20px;font-weight:500">
                {{ $total }} OS
            </span>
            @if ($conc > 0)
            <span style="background:#dcfce7;color:#15803d;font-size:11px;padding:1px 8px;border-radius:20px;font-weight:500">
                {{ $conc }} concluída{{ $conc > 1 ? 's' : '' }}
            </span>
            @endif
            @if ($pend > 0)
            <span style="background:#fef3c7;color:#92400e;font-size:11px;padding:1px 8px;border-radius:20px;font-weight:500">
                {{ $pend }} pendente{{ $pend > 1 ? 's' : '' }}
            </span>
            @endif
            @endif
        </div>

        {{-- Lista de OS do dia --}}
        @if ($os_do_dia->isEmpty())
        <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius);padding:16px 20px;color:#94a3b8;font-size:13px">
            Nenhuma OS agendada para este dia.
            @if (auth()->user()->ehGestor())
            <a href="/os/nova" style="color:#0d9488;margin-left:6px">Agendar →</a>
            @endif
        </div>
        @else
        <div class="card" style="overflow:hidden">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Horário</th>
                            <th>Código</th>
                            <th>Profissional</th>
                            <th>Unidade</th>
                            <th>Atividade</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($os_do_dia->sortBy('hora_agendamento') as $os)
                    @php
                        $statusMap = [
                            'nao_iniciado'  => ['badge-gray',  'Não iniciado'],
                            'iniciado'      => ['badge-amber', 'Em andamento'],
                            'concluido'     => ['badge-green', 'Concluído'],
                            'nao_executado' => ['badge-red',   'Não executado'],
                        ];
                        [$badgeCls, $badgeLbl] = $statusMap[$os->status];
                    @endphp
                    <tr>
                        <td style="font-weight:600;color:#475569;font-family:monospace;font-size:13px">
                            {{ $os->hora_agendamento ? \Carbon\Carbon::parse($os->hora_agendamento)->format('H:i') : '—' }}
                        </td>
                        <td>
                            <a href="/os/{{ $os->id }}" style="font-weight:600;color:#0d9488;font-family:monospace;font-size:12.5px">
                                {{ $os->codigo }}
                            </a>
                        </td>
                        <td>{{ $os->profissional->nome }}</td>
                        <td>{{ $os->unidade->nome }}</td>
                        <td>{{ $os->atividade->nome }}</td>
                        <td><span class="badge {{ $badgeCls }}">{{ $badgeLbl }}</span></td>
                        <td>
                            <a href="/os/{{ $os->id }}" class="btn btn-ghost btn-sm">Ver</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
    @endforeach
</div>
@endsection
