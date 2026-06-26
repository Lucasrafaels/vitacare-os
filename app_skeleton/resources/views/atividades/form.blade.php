@extends('layouts.app')

@section('content')
@php $editando = $atv !== null; @endphp
<div class="topbar">
    <a href="/atividades" style="color:#64748b;font-size:13px">← Atividades</a>
    <span class="topbar-title" style="margin-left:12px">{{ $editando ? 'Editar Atividade' : 'Nova Atividade' }}</span>
</div>
<div class="page-body" style="max-width:560px">
    @if ($errors->any())
    <div class="alert alert-erro">{{ $errors->first() }}</div>
    @endif
    <div class="card">
        <div class="card-header"><span class="card-title">{{ $editando ? 'Editar atividade' : 'Cadastrar nova atividade' }}</span></div>
        <div class="card-body">
            <form method="POST" action="{{ $editando ? "/atividades/{$atv->id}/editar" : '/atividades/nova' }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group full">
                        <label>Nome da Atividade <span class="req">*</span></label>
                        <input type="text" name="nome" required value="{{ $atv?->nome ?? old('nome', '') }}" placeholder="Ex: Vacinação contra Influenza">
                    </div>
                    <div class="form-group full">
                        <label>Status <span class="req">*</span></label>
                        <select name="status" required>
                            <option value="ativa" {{ ($atv?->status ?? old('status', 'ativa')) === 'ativa' ? 'selected' : '' }}>Ativa</option>
                            <option value="inativa" {{ ($atv?->status ?? old('status')) === 'inativa' ? 'selected' : '' }}>Inativa</option>
                        </select>
                    </div>
                </div>
                <hr class="divider">
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ $editando ? '💾 Salvar' : '✓ Cadastrar' }}</button>
                    <a href="/atividades" class="btn btn-outline">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
