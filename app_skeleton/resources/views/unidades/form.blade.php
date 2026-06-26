@extends('layouts.app')

@section('content')
@php $editando = $uni !== null; @endphp
<div class="topbar">
    <a href="/unidades" style="color:#64748b;font-size:13px">← Unidades</a>
    <span class="topbar-title" style="margin-left:12px">{{ $editando ? 'Editar Unidade' : 'Nova Unidade' }}</span>
</div>
<div class="page-body" style="max-width:560px">
    @if ($errors->any())
    <div class="alert alert-erro">{{ $errors->first() }}</div>
    @endif
    <div class="card">
        <div class="card-header"><span class="card-title">{{ $editando ? 'Editar dados da unidade' : 'Cadastrar unidade de saúde' }}</span></div>
        <div class="card-body">
            <form method="POST" action="{{ $editando ? "/unidades/{$uni->id}/editar" : '/unidades/nova' }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group full">
                        <label>Nome da Unidade <span class="req">*</span></label>
                        <input type="text" name="nome" required value="{{ $uni?->nome ?? old('nome', '') }}" placeholder="Ex: UBS Centro">
                    </div>
                    <div class="form-group">
                        <label>Cidade <span class="req">*</span></label>
                        <input type="text" name="cidade" required value="{{ $uni?->cidade ?? old('cidade', '') }}">
                    </div>
                    <div class="form-group">
                        <label>Status <span class="req">*</span></label>
                        <select name="status" required>
                            <option value="ativa" {{ ($uni?->status ?? old('status', 'ativa')) === 'ativa' ? 'selected' : '' }}>Ativa</option>
                            <option value="inativa" {{ ($uni?->status ?? old('status')) === 'inativa' ? 'selected' : '' }}>Inativa</option>
                        </select>
                    </div>
                    <div class="form-group full">
                        <label>Endereço completo</label>
                        <textarea name="endereco" rows="2">{{ $uni?->endereco ?? old('endereco', '') }}</textarea>
                    </div>
                </div>
                <hr class="divider">
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ $editando ? '💾 Salvar' : '✓ Cadastrar' }}</button>
                    <a href="/unidades" class="btn btn-outline">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
