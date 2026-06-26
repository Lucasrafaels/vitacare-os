@extends('layouts.app')

@section('content')
<div class="topbar">
    <span class="topbar-title">Profissionais</span>
    <div class="topbar-actions">
        <a href="/profissionais/novo" class="btn btn-primary btn-sm">+ Novo profissional</a>
    </div>
</div>
<div class="page-body">
    <div class="card">
        @if ($lista->isEmpty())
        <div class="empty-state">Nenhum profissional cadastrado.</div>
        @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nome</th><th>E-mail</th><th>Cargo</th><th>Perfil</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach ($lista as $p)
                <tr>
                    <td><a href="/profissionais/{{ $p->id }}" style="font-weight:600;color:#0d9488;text-decoration:none">{{ $p->nome }}</a></td>
                    <td>{{ $p->email }}</td>
                    <td>{{ $p->cargo ?: '—' }}</td>
                    <td><span class="badge {{ $p->perfil === 'gestor' ? 'badge-blue' : 'badge-teal' }}">{{ ucfirst($p->perfil) }}</span></td>
                    <td><span class="badge {{ $p->status === 'ativo' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($p->status) }}</span></td>
                    <td>
                        <div class="flex gap-2">
                            <a href="/profissionais/{{ $p->id }}/editar" class="btn btn-ghost btn-sm">Editar</a>
                            @if ($p->id != auth()->id())
                            <form method="POST" action="/profissionais/{{ $p->id }}/excluir" onsubmit="return confirm('Desativar este profissional?')">
                                @csrf
                                <button type="submit" class="btn btn-ghost btn-sm" style="color:#ef4444">Desativar</button>
                            </form>
                            @endif
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
