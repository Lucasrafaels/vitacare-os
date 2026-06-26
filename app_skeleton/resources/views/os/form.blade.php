@extends('layouts.app')

@section('content')
@php $editando = $os !== null; @endphp
<div class="topbar">
    <a href="{{ $editando ? "/os/{$os->id}" : '/os' }}" style="color:#64748b;font-size:13px">
        ← {{ $editando ? 'Voltar para OS' : 'Ordens de Serviço' }}
    </a>
    <span class="topbar-title" style="margin-left:12px">
        {{ $editando ? 'Editar OS · ' . $os->codigo : 'Nova Ordem de Serviço' }}
    </span>
</div>
<div class="page-body" style="max-width:700px">
    @if ($errors->any())
    <div class="alert alert-erro">{{ $errors->first() }}</div>
    @endif
    <div class="card">
        <div class="card-header">
            <span class="card-title">{{ $editando ? 'Editar dados da OS' : 'Criar nova OS' }}</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ $editando ? "/os/{$os->id}/editar" : '/os/nova' }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Profissional (Facilitador) <span class="req">*</span></label>
                        <select name="profissional_id" required>
                            <option value="">Selecione…</option>
                            @foreach ($profissionais as $p)
                            <option value="{{ $p->id }}" {{ ($os?->profissional_id ?? old('profissional_id')) == $p->id ? 'selected' : '' }}>
                                {{ $p->nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Unidade de Saúde <span class="req">*</span></label>
                        <select name="unidade_id" required>
                            <option value="">Selecione…</option>
                            @foreach ($unidades as $u)
                            <option value="{{ $u->id }}" {{ ($os?->unidade_id ?? old('unidade_id')) == $u->id ? 'selected' : '' }}>
                                {{ $u->nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Atividade <span class="req">*</span></label>
                        <select name="atividade_id" required>
                            <option value="">Selecione…</option>
                            @foreach ($atividades as $a)
                            <option value="{{ $a->id }}" {{ ($os?->atividade_id ?? old('atividade_id')) == $a->id ? 'selected' : '' }}>
                                {{ $a->nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group"></div>
                    <div class="form-group">
                        <label>Data de Agendamento</label>
                        <input type="date" name="data_agendamento"
                               value="{{ $os?->data_agendamento?->format('Y-m-d') ?? old('data_agendamento', date('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label>Hora de Agendamento</label>
                        <input type="time" name="hora_agendamento"
                               value="{{ $os?->hora_agendamento ?? old('hora_agendamento', '') }}">
                    </div>
                    <div class="form-group full">
                        <label>Observações</label>
                        <textarea name="observacoes" rows="3" placeholder="Instruções especiais, informações do paciente…">{{ $os?->observacoes ?? old('observacoes', '') }}</textarea>
                    </div>
                </div>
                <hr class="divider">
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        {{ $editando ? '💾 Salvar alterações' : '✓ Criar OS' }}
                    </button>
                    <a href="{{ $editando ? "/os/{$os->id}" : '/os' }}" class="btn btn-outline">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
