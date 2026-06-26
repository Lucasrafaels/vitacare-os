@extends('layouts.app')

@section('content')
<div class="topbar">
    <a href="/relatorios/os-profissional" style="color:#64748b;font-size:13px">← Relatório por profissional</a>
    <span class="topbar-title" style="margin-left:12px">Tempo Médio por Unidade</span>
</div>
<div class="page-body">
    <form method="GET" action="/relatorios/tempo-medio" class="filter-bar">
        <div class="form-group">
            <label>Data inicial</label>
            <input type="date" name="data_ini" value="{{ $data_ini }}">
        </div>
        <div class="form-group">
            <label>Data final</label>
            <input type="date" name="data_fim" value="{{ $data_fim }}">
        </div>
        <div class="form-group" style="justify-content:flex-end;flex:none">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
        </div>
    </form>

    <div class="card">
        @if ($porUnidade->isEmpty())
        <div class="empty-state">
            <div style="font-weight:600;color:#1e293b;margin-bottom:4px">Sem dados suficientes</div>
            <div class="text-sm text-muted">Nenhuma OS concluída no período selecionado.</div>
        </div>
        @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Unidade de Saúde</th>
                        <th>OS Concluídas</th>
                        <th>Tempo Médio de Atendimento</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($porUnidade as $linha)
                <tr>
                    <td><strong>{{ $linha['unidade'] }}</strong></td>
                    <td>{{ $linha['total_os'] }}</td>
                    <td>
                        @php
                            $h = intdiv($linha['tempo_medio_min'], 60);
                            $m = $linha['tempo_medio_min'] % 60;
                        @endphp
                        <span class="badge badge-teal">{{ $h > 0 ? "{$h}h " : '' }}{{ $m }}min</span>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:12px 16px;font-size:12.5px;color:#64748b;border-top:1px solid #e2e8f0">
            Tempo médio calculado pela diferença entre o início e a conclusão de cada atendimento, no período selecionado.
        </div>
        @endif
    </div>
</div>
@endsection
