<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AtividadeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrdemServicoController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfissionalController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\UnidadeController;
use Illuminate\Support\Facades\Route;

/* ------------------------------- Portal público -------------------------------- */

Route::get('/', [PortalController::class, 'index']);
Route::get('/pesquisa', [PortalController::class, 'pesquisar']);

/* ---------------------------------- Autenticação -------------------------------- */

Route::get('/login', [AuthController::class, 'tela'])->name('login');
Route::post('/login', [AuthController::class, 'entrar']);
Route::post('/logout', [AuthController::class, 'sair']);

/* ------------------------------- Área autenticada -------------------------------- */

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Ordens de Serviço
    Route::get('/os', [OrdemServicoController::class, 'index']);
    Route::get('/os/nova', [OrdemServicoController::class, 'nova']);
    Route::post('/os/nova', [OrdemServicoController::class, 'criar']);
    Route::get('/os/{os}', [OrdemServicoController::class, 'show']);
    Route::get('/os/{os}/editar', [OrdemServicoController::class, 'editarForm']);
    Route::post('/os/{os}/editar', [OrdemServicoController::class, 'editar']);
    Route::post('/os/{os}/excluir', [OrdemServicoController::class, 'excluir']);
    Route::post('/os/{os}/duplicar', [OrdemServicoController::class, 'duplicar']);
    Route::post('/os/{os}/iniciar', [OrdemServicoController::class, 'iniciar']);
    Route::post('/os/{os}/concluir', [OrdemServicoController::class, 'concluir']);
    Route::post('/os/{os}/nao-executar', [OrdemServicoController::class, 'naoExecutar']);
    Route::get('/os/{os}/pdf', [OrdemServicoController::class, 'pdf']);

    // Agenda futura — visão de OS agendadas por dia
    Route::get('/agenda', [OrdemServicoController::class, 'agenda']);

    // Área de gestão (apenas gestores)
    Route::middleware('gestor')->group(function () {
        Route::get('/profissionais', [ProfissionalController::class, 'index']);
        Route::get('/profissionais/novo', [ProfissionalController::class, 'novo']);
        Route::post('/profissionais/novo', [ProfissionalController::class, 'criar']);
        Route::get('/profissionais/{prof}', [ProfissionalController::class, 'show']);
        Route::get('/profissionais/{prof}/editar', [ProfissionalController::class, 'editarForm']);
        Route::post('/profissionais/{prof}/editar', [ProfissionalController::class, 'editar']);
        Route::post('/profissionais/{prof}/excluir', [ProfissionalController::class, 'excluir']);

        Route::get('/unidades', [UnidadeController::class, 'index']);
        Route::get('/unidades/nova', [UnidadeController::class, 'nova']);
        Route::post('/unidades/nova', [UnidadeController::class, 'criar']);
        Route::get('/unidades/{uni}', [UnidadeController::class, 'show']);
        Route::get('/unidades/{uni}/editar', [UnidadeController::class, 'editarForm']);
        Route::post('/unidades/{uni}/editar', [UnidadeController::class, 'editar']);
        Route::post('/unidades/{uni}/excluir', [UnidadeController::class, 'excluir']);

        Route::get('/atividades', [AtividadeController::class, 'index']);
        Route::get('/atividades/nova', [AtividadeController::class, 'nova']);
        Route::post('/atividades/nova', [AtividadeController::class, 'criar']);
        Route::get('/atividades/{atv}', [AtividadeController::class, 'show']);
        Route::get('/atividades/{atv}/editar', [AtividadeController::class, 'editarForm']);
        Route::post('/atividades/{atv}/editar', [AtividadeController::class, 'editar']);
        Route::post('/atividades/{atv}/excluir', [AtividadeController::class, 'excluir']);

        Route::get('/relatorios/os-profissional', [RelatorioController::class, 'porProfissional']);
        Route::get('/relatorios/tempo-medio', [RelatorioController::class, 'tempoMedio']);
    });
});

