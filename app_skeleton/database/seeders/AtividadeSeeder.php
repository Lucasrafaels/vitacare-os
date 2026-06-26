<?php

namespace Database\Seeders;

use App\Models\Atividade;
use Illuminate\Database\Seeder;

class AtividadeSeeder extends Seeder
{
    public function run(): void
    {
        $atividades = [
            'Visita domiciliar',
            'Consulta de rotina',
            'Vacinação',
            'Coleta de exames',
            'Treinamento de equipe',
            'Manutenção de equipamento',
        ];

        foreach ($atividades as $nome) {
            Atividade::create(['nome' => $nome, 'status' => 'ativa']);
        }
    }
}
