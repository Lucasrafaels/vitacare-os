<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordens_servico', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // ex: OS-2026-0001

            $table->foreignId('profissional_id')->constrained('profissionais');
            $table->foreignId('unidade_id')->constrained('unidades');
            $table->foreignId('atividade_id')->constrained('atividades');
            $table->foreignId('gestor_id')->nullable()->constrained('profissionais');

            $table->date('data_agendamento')->nullable();
            $table->time('hora_agendamento')->nullable();
            $table->text('observacoes')->nullable();

            // Ciclo de vida: nao_iniciado -> iniciado -> concluido | nao_executado
            $table->enum('status', ['nao_iniciado', 'iniciado', 'concluido', 'nao_executado'])
                  ->default('nao_iniciado');

            $table->timestamp('iniciado_em')->nullable();
            $table->timestamp('concluido_em')->nullable();

            // Ficha de atendimento (obrigatória para concluir)
            $table->string('tipo_intervencao')->nullable();
            $table->text('resolucao')->nullable();
            $table->string('contato_local')->nullable();
            $table->text('ficha_obs')->nullable();

            // Motivo obrigatório quando não executada
            $table->text('motivo_nao_execucao')->nullable();

            $table->timestamps();

            $table->index(['data_agendamento', 'profissional_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordens_servico');
    }
};
