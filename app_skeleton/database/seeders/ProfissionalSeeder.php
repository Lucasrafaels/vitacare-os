<?php

namespace Database\Seeders;

use App\Models\Profissional;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProfissionalSeeder extends Seeder
{
    public function run(): void
    {
        $senha = Hash::make('vitacare123');

        Profissional::create([
            'nome'   => 'Renata Fonseca',
            'email'  => 'gestor@vitacare.dev',
            'senha'  => $senha,
            'cargo'  => 'Coordenadora de Campo',
            'perfil' => 'gestor',
            'status' => 'ativo',
        ]);

        Profissional::create([
            'nome'   => 'Carlos Eduardo Nunes',
            'email'  => 'carlos@vitacare.dev',
            'senha'  => $senha,
            'cargo'  => 'Facilitador de Campo',
            'perfil' => 'facilitador',
            'status' => 'ativo',
        ]);

        Profissional::create([
            'nome'   => 'Ana Beatriz Costa',
            'email'  => 'ana@vitacare.dev',
            'senha'  => $senha,
            'cargo'  => 'Facilitadora de Campo',
            'perfil' => 'facilitador',
            'status' => 'ativo',
        ]);

        Profissional::create([
            'nome'   => 'Marcos Oliveira',
            'email'  => 'marcos@vitacare.dev',
            'senha'  => $senha,
            'cargo'  => 'Facilitador de Campo',
            'perfil' => 'facilitador',
            'status' => 'ativo',
        ]);

        Profissional::create([
            'nome'   => 'Juliana Melo',
            'email'  => 'juliana@vitacare.dev',
            'senha'  => $senha,
            'cargo'  => 'Facilitadora de Campo',
            'perfil' => 'facilitador',
            'status' => 'inativo',
        ]);
    }
}
