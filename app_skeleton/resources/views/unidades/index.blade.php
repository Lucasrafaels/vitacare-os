@extends('layouts.app')

@section('content')
<div class="topbar">
    <span class="topbar-title">Unidades de Saúde</span>
    <div class="topbar-actions">
        <a href="/unidades/nova" class="btn btn-primary btn-sm">+ Nova unidade</a>
    </div>
</div>
<div class="page-body">
    <div class="card">
        @if ($lista->isEmpty())
        <div class="empty-state">Nenhuma unidade cadastrada.</div>
        @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nome</th><th>Cidade</th><th>Endereço</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach ($lista as $u)
                <tr>
                    <td><a href="/unidades/{{ $u->id }}" style="font-weight:600;color:#0d9488;text-decoration:none">{{ $u->nome }}</a></td>
                    <td>{{ $u->cidade }}</td>
                    <td>{{ $u->endereco ?: '—' }}</td>
                    <td><span class="badge {{ $u->status === 'ativa' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($u->status) }}</span></td>
                    <td>
                        <div class="flex gap-2">
                            <a href="/unidades/{{ $u->id }}/editar" class="btn btn-ghost btn-sm">Editar</a>
                            <form method="POST" action="/unidades/{{ $u->id }}/excluir" onsubmit="return confirm('Desativar esta unidade?')">
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
