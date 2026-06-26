@extends('layouts.app')

@section('content')
<div class="topbar">
    <a href="/profissionais" style="color:#64748b;font-size:13px">← Profissionais</a>
    <span class="topbar-title" style="margin-left:12px">{{ $prof->nome }}</span>
    <div class="topbar-actions">
        <a href="/profissionais/{{ $prof->id }}/editar" class="btn btn-outline btn-sm">Editar</a>
        @if ($prof->id != auth()->id())
        <form method="POST" action="/profissionais/{{ $prof->id }}/excluir"
              style="display:inline" onsubmit="return confirm('Desativar este profissional?')">
            @csrf
            <button type="submit" class="btn btn-ghost btn-sm" style="color:#ef4444">Desativar</button>
        </form>
        @endif
    </div>
</div>

<div class="page-body">
    <div style="display:grid;grid-template-columns:1fr 2fr;gap:20px">

        {{-- Cartão de perfil --}}
        <div>
            <div class="card">
                <div class="card-body" style="text-align:center;padding:28px 20px">
                    <div style="width:64px;height:64px;border-radius:50%;background:#e0f2fe;
                                display:flex;align-items:center;justify-content:center;
                                margin:0 auto 14px;font-size:26px;font-weight:600;color:#0369a1">
                        {{ strtoupper(substr($prof->nome, 0, 1)) }}
                    </div>
                    <div style="font-size:16px;font-weight:600;color:#1e293b">{{ $prof->nome }}</div>
                    <div style="font-size:13px;color:#64748b;margin-top:4px">{{ $prof->cargo ?: 'Sem cargo definido' }}</div>
                    <div style="margin-top:10px;display:flex;gap:8px;justify-content:center;flex-wrap:wrap">
                        <span class="badge {{ $prof->perfil === 'gestor' ? 'badge-blue' : 'badge-teal' }}">
                            {{ ucfirst($prof->perfil) }}
                        </span>
                        <span class="badge {{ $prof->status === 'ativo' ? 'badge-green' : 'badge-gray' }}">
                            {{ ucfirst($prof->status) }}
                        </span>
                    </div>
                </div>
                <div style="border-top:1px solid #e2e8f0;padding:16px 20px">
                    <div style="font-size:11px;font-weight:600;color:#94a3b8;
                                text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px">
                        Dados de contato
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#475569">
                        <span style="color:#94a3b8">✉</span> {{ $prof->email }}
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
                    <a href="/os?profissional={{ $prof->id }}" class="btn btn-outline btn-sm">Ver todas</a>
                </div>
                @if ($ultimas->isEmpty())
                <div class="card-body">
                    <div class="empty-state">Nenhuma OS registrada para este profissional.</div>
                </div>
                @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Unidade</th>
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
                            <td>{{ $os->unidade->nome }}</td>
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
