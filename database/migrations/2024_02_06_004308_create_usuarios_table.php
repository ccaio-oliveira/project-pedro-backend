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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('sobrenome');
            $table->string('nome_completo');
            $table->string('email', 50)->unique();
            $table->string('cpf', 11)->unique();
            $table->unsignedBigInteger('perfil_usuario');
            $table->dateTime('data_criacao')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();

            $table->foreign('perfil_usuario')->references('id')->on('perfil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign(['perfil_usuario']);
        });

        Schema::dropIfExists('usuarios');
    }
};
