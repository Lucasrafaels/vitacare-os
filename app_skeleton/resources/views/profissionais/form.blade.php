@extends('layouts.app')

@section('content')
@php $editando = $prof !== null; @endphp
<div class="topbar">
    <a href="/profissionais" style="color:#64748b;font-size:13px">← Profissionais</a>
    <span class="topbar-title" style="margin-left:12px">{{ $editando ? 'Editar Profissional' : 'Novo Profissional' }}</span>
</div>
<div class="page-body" style="max-width:600px">
    @if ($errors->any())
    <div class="alert alert-erro">{{ $errors->first() }}</div>
    @endif
    <div class="card">
        <div class="card-header"><span class="card-title">{{ $editando ? 'Editar dados' : 'Cadastrar profissional' }}</span></div>
        <div class="card-body">
            <form method="POST" action="{{ $editando ? "/profissionais/{$prof->id}/editar" : '/profissionais/novo' }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group full">
                        <label>Nome completo <span class="req">*</span></label>
                        <input type="text" name="nome" required value="{{ $prof?->nome ?? old('nome', '') }}">
                    </div>
                    <div class="form-group">
                        <label>E-mail <span class="req">*</span></label>
                        <input type="email" name="email" required value="{{ $prof?->email ?? old('email', '') }}">
                    </div>
                    <div class="form-group">
                        <label>Cargo</label>
                        <input type="text" name="cargo" value="{{ $prof?->cargo ?? old('cargo', '') }}" placeholder="Ex: Facilitador Senior">
                    </div>
                    <div class="form-group">
                        <label>Perfil <span class="req">*</span></label>
                        <select name="perfil" required>
                            <option value="facilitador" {{ ($prof?->perfil ?? old('perfil')) === 'facilitador' ? 'selected' : '' }}>Facilitador</option>
                            <option value="gestor" {{ ($prof?->perfil ?? old('perfil')) === 'gestor' ? 'selected' : '' }}>Gestor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status <span class="req">*</span></label>
                        <select name="status" required>
                            <option value="ativo" {{ ($prof?->status ?? old('status', 'ativo')) === 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ ($prof?->status ?? old('status')) === 'inativo' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                    <div class="form-group full">
                        <label>Senha {{ $editando ? '(deixe em branco para manter)' : '' }} {!! !$editando ? '<span class="req">*</span>' : '' !!}</label>
                        <input type="password" name="senha" {{ !$editando ? 'required' : '' }} minlength="6" placeholder="Mínimo 6 caracteres">
                    </div>
                </div>
                <hr class="divider">
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ $editando ? '💾 Salvar' : '✓ Cadastrar' }}</button>
                    <a href="/profissionais" class="btn btn-outline">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
