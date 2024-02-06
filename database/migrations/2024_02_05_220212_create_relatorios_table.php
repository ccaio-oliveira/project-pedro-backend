<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('relatorios', function (Blueprint $table) {
            $table->id();
            $table->string('assunto');
            $table->unsignedBigInteger('aberto_por');
            $table->unsignedBigInteger('atrelado_a');
            $table->string('nome_paciente');
            $table->unsignedBigInteger('grau');
            $table->unsignedBigInteger('status');
            $table->unsignedBigInteger('arquivo')->nullable();
            $table->dateTime('data_criacao');
            $table->timestamps();

            $table->foreign('aberto_por')->references('id')->on('usuarios');
            $table->foreign('atrelado_a')->references('id')->on('usuarios');
            $table->foreign('grau')->references('id')->on('grau_prioridade');
            $table->foreign('status')->references('id')->on('relatorios_status');
            $table->foreign('arquivo')->references('id')->on('relatorios_arquivos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('relatorios', function (Blueprint $table) {
            $table->dropForeign(['aberto_por']);
            $table->dropForeign(['atrelado_a']);
            $table->dropForeign(['grau']);
            $table->dropForeign(['status']);
            $table->dropForeign(['arquivo']);
        });

        Schema::dropIfExists('relatorios');
    }
};
