<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profissionais', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('senha'); // hash (password)
            $table->string('cargo')->nullable();
            $table->enum('perfil', ['gestor', 'facilitador'])->default('facilitador');
            $table->enum('status', ['ativo', 'inativo'])->default('ativo');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profissionais');
    }
};
