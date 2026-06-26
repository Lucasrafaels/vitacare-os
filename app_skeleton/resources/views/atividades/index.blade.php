@extends('layouts.app')

@section('content')
<div class="topbar">
    <span class="topbar-title">Atividades</span>
    <div class="topbar-actions">
        <a href="/atividades/nova" class="btn btn-primary btn-sm">+ Nova atividade</a>
    </div>
</div>
<div class="page-body">
    <div class="card">
        @if ($lista->isEmpty())
        <div class="empty-state">Nenhuma atividade cadastrada.</div>
        @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nome</th><th>OS vinculadas</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach ($lista as $a)
                <tr>
                    <td><a href="/atividades/{{ $a->id }}" style="font-weight:600;color:#0d9488;text-decoration:none">{{ $a->nome }}</a></td>
                    <td>{{ $a->ordens_servico_count }}</td>
                    <td><span class="badge {{ $a->status === 'ativa' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($a->status) }}</span></td>
                    <td>
                        <div class="flex gap-2">
                            <a href="/atividades/{{ $a->id }}/editar" class="btn btn-ghost btn-sm">Editar</a>
                            <form method="POST" action="/atividades/{{ $a->id }}/excluir" onsubmit="return confirm('Desativar esta atividade?')">
                                @csrf
                                <button type="submit" class="btn btn-ghost btn-sm" style="color:#ef4444">Desativar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
