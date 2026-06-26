<?php

namespace Database\Seeders;

use App\Models\Unidade;
use Illuminate\Database\Seeder;

class UnidadeSeeder extends Seeder
{
    public function run(): void
    {
        $unidades = [
            ['nome' => 'UBS Centro',        'cidade' => 'Vassouras', 'endereco' => 'Rua Coronel José Luiz, 120'],
            ['nome' => 'UBS Mello Viana',   'cidade' => 'Vassouras', 'endereco' => 'Av. Mello Viana, 450'],
            ['nome' => 'UBS Sebastião Reis','cidade' => 'Vassouras', 'endereco' => 'Rua Sebastião Reis, 88'],
            ['nome' => 'UBS Andrade Junior','cidade' => 'Mendes',    'endereco' => 'Praça Central, s/n'],
        ];

        foreach ($unidades as $u) {
            Unidade::create([...$u, 'status' => 'ativa']);
        }
    }
}
