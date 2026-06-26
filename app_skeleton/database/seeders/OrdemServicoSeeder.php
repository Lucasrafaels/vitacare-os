<?php

namespace Database\Seeders;

use App\Models\Atividade;
use App\Models\OrdemServico;
use App\Models\Profissional;
use App\Models\Unidade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class OrdemServicoSeeder extends Seeder
{
    public function run(): void
    {
        $gestor   = Profissional::where('perfil', 'gestor')->first();
        $carlos   = Profissional::where('email', 'carlos@vitacare.dev')->first();
        $ana      = Profissional::where('email', 'ana@vitacare.dev')->first();
        $unidades = Unidade::all();
        $atividades = Atividade::all();
        $hoje = Carbon::today();

        // ---- OS de HOJE (para o dashboard nascer populado) ----
        $planoHoje = [
            ['prof' => $carlos, 'status' => 'concluido',     'hora' => '07:00'],
            ['prof' => $carlos, 'status' => 'iniciado',      'hora' => '10:30'],
            ['prof' => $carlos, 'status' => 'nao_iniciado',  'hora' => '14:00'],
            ['prof' => $ana,    'status' => 'concluido',     'hora' => '08:30'],
            ['prof' => $ana,    'status' => 'nao_executado', 'hora' => '11:00'],
            ['prof' => $ana,    'status' => 'nao_iniciado',  'hora' => '15:30'],
        ];

        foreach ($planoHoje as $item) {
            $this->criarOs($gestor, $item['prof'], $unidades->random(), $atividades->random(), $hoje, $item['hora'], $item['status']);
        }

        // ---- OS FUTURAS (para a agenda aparecer populada — #10 agendamento) ----
        for ($i = 1; $i <= 6; $i++) {
            $dia  = $hoje->copy()->addDays($i);
            $prof = $i % 2 === 0 ? $carlos : $ana;
            $qtd  = $i <= 2 ? 3 : 2;
            $horas = ['08:00', '10:30', '14:00'];
            for ($h = 0; $h < $qtd; $h++) {
                $this->criarOs($gestor, $prof, $unidades->random(), $atividades->random(), $dia, $horas[$h], 'nao_iniciado');
            }
        }

        // ---- OS PASSADAS (histórico para relatórios) ----
        for ($i = 1; $i <= 15; $i++) {
            $dia    = $hoje->copy()->subDays($i);
            $prof   = $i % 2 === 0 ? $carlos : $ana;
            $status = $i % 4 === 0 ? 'nao_executado' : 'concluido';
            $qtd    = $i <= 5 ? 3 : 2;
            for ($h = 0; $h < $qtd; $h++) {
                $this->criarOs($gestor, $prof, $unidades->random(), $atividades->random(), $dia, '09:00', $status);
            }
        }
    }

    private function criarOs(Profissional $gestor, Profissional $prof, Unidade $unidade, Atividade $atividade, Carbon $data, string $hora, string $status): void
    {
        $base = [
            'codigo'           => OrdemServico::gerarCodigo(),
            'profissional_id'  => $prof->id,
            'unidade_id'       => $unidade->id,
            'atividade_id'     => $atividade->id,
            'gestor_id'        => $gestor->id,
            'data_agendamento' => $data->toDateString(),
            'hora_agendamento' => $hora,
            'observacoes'      => 'Atendimento de demonstração gerado pelo seeder.',
            'status'           => $status,
        ];

        if (in_array($status, ['iniciado', 'concluido', 'nao_executado'])) {
            $base['iniciado_em'] = $data->copy()->setTimeFromTimeString($hora)->addMinutes(5);
        }

        if ($status === 'concluido') {
            $base['concluido_em']     = $data->copy()->setTimeFromTimeString($hora)->addMinutes(rand(30, 75));
            $base['tipo_intervencao'] = 'Visita domiciliar';
            $base['resolucao']        = 'Atendimento realizado conforme protocolo padrão da unidade.';
            $base['contato_local']    = 'Enfermeira responsável pelo turno';
            $base['ficha_obs']        = 'Paciente cooperativo. Retorno agendado em 30 dias.';
        }

        if ($status === 'nao_executado') {
            $base['motivo_nao_execucao'] = 'Paciente ausente no momento da visita. Necessário reagendar.';
        }

        OrdemServico::create($base);
    }
}
