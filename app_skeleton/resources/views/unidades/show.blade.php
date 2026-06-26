@extends('layouts.app')

@section('content')
<div class="topbar">
    <a href="/unidades" style="color:#64748b;font-size:13px">← Unidades</a>
    <span class="topbar-title" style="margin-left:12px">{{ $uni->nome }}</span>
    <div class="topbar-actions">
        <a href="/unidades/{{ $uni->id }}/editar" class="btn btn-outline btn-sm">Editar</a>
        <form method="POST" action="/unidades/{{ $uni->id }}/excluir"
              style="display:inline" onsubmit="return confirm('Desativar esta unidade?')">
            @csrf
            <button type="submit" class="btn btn-ghost btn-sm" style="color:#ef4444">Desativar</button>
        </form>
    </div>
</div>

<div class="page-body">
    <div style="display:grid;grid-template-columns:1fr 2fr;gap:20px">

        {{-- Cartão de dados --}}
        <div>
            <div class="card">
                <div class="card-body" style="padding:24px 20px">
                    <div style="width:52px;height:52px;border-radius:10px;background:#f0fdfa;
                                display:flex;align-items:center;justify-content:center;
                                margin-bottom:14px;font-size:24px">🏥</div>
                    <div style="font-size:16px;font-weight:600;color:#1e293b;margin-bottom:4px">
                        {{ $uni->nome }}
                    </div>
                    <div style="font-size:13px;color:#64748b">{{ $uni->cidade }}</div>
                    @if ($uni->endereco)
                    <div style="font-size:12.5px;color:#94a3b8;margin-top:6px">{{ $uni->endereco }}</div>
                    @endif
                    <div style="margin-top:12px">
                        <span class="badge {{ $uni->status === 'ativa' ? 'badge-green' : 'badge-gray' }}">
                            {{ ucfirst($uni->status) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Totais --}}
            <div class="card" style="margin-top:16px">
                <div class="card-header"><span class="card-title">Resumo de OS</span></div>
                <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                    <div style="text-align:center;background:#f8fafc;border-radius:8px;padding:12px">
                        <div style="font-size:22px;font-weight:600;color:#1e293b">{{ $totais['total'] }}</div>
                        <div style="font-size:11.5px;color:#64748b">Total</div>
                    </div>
                    <div style="text-align:center;background:#dcfce7;border-radius:8px;padding:12px">
                        <div style="font-size:22px;font-weight:600;color:#15803d">{{ $totais['concluidas'] }}</div>
                        <div style="font-size:11.5px;color:#15803d">Concluídas</div>
                    </div>
                    <div style="text-align:center;background:#fef3c7;border-radius:8px;padding:12px">
                        <div style="font-size:22px;font-weight:600;color:#92400e">{{ $totais['pendentes'] }}</div>
                        <div style="font-size:11.5px;color:#92400e">Pendentes</div>
                    </div>
                    <div style="text-align:center;background:#fee2e2;border-radius:8px;padding:12px">
                        <div style="font-size:22px;font-weight:600;color:#b91c1c">{{ $totais['nao_exec'] }}</div>
                        <div style="font-size:11.5px;color:#b91c1c">Não exec.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Últimas OS --}}
        <div>
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between">
                    <span class="card-title">Últimas Ordens de Serviço</span>
                    <a href="/os?unidade={{ $uni->id }}" class="btn btn-outline btn-sm">Ver todas</a>
                </div>
                @if ($ultimas->isEmpty())
                <div class="card-body">
                    <div class="empty-state">Nenhuma OS registrada para esta unidade.</div>
                </div>
                @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Profissional</th>
                                <th>Atividade</th>
                                <th>Data</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($ultimas as $os)
                        @php
                            $statusMap = [
                                'nao_iniciado'  => ['badge-gray',  'Não iniciado'],
                                'iniciado'      => ['badge-amber', 'Em andamento'],
                                'concluido'     => ['badge-green', 'Concluído'],
                                'nao_executado' => ['badge-red',   'Não executado'],
                            ];
                            [$badgeCls, $badgeLabel] = $statusMap[$os->status];
                        @endphp
                        <tr>
                            <td><code style="font-size:12px">{{ $os->codigo }}</code></td>
                            <td>{{ $os->profissional->nome }}</td>
                            <td>{{ $os->atividade->nome }}</td>
                            <td>{{ $os->data_agendamento ? \Carbon\Carbon::parse($os->data_agendamento)->format('d/m/Y') : '—' }}</td>
                            <td><span class="badge {{ $badgeCls }}">{{ $badgeLabel }}</span></td>
                            <td><a href="/os/{{ $os->id }}" class="btn btn-ghost btn-sm">Ver</a></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
