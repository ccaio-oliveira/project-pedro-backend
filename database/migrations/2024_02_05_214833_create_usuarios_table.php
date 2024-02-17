<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nome');
            $table->string('sobrenome');
            $table->string('nome_completo');
            $table->integer('foto_perfil')->default(0);
            $table->string('email', 50)->unique();
            $table->string('cpf', 11)->unique();
            $table->integer('perfil_usuario');
            $table->dateTime('data_criacao')->nullable();
            $table->integer('status')->default(0);

            $table->foreign('perfil_usuario')->references('id')->on('perfil');

            $table->engine = 'InnoDB';
        });

        DB::table('usuarios')->insert([
            [
                'nome' => 'Admin',
                'sobrenome' => 'Admin',
                'nome_completo' => 'Admin Admin',
                'email' => 'admin@admin.com',
                'cpf' => '11111111111',
                'perfil_usuario' => 1,
                'status' => 1,
            ],
            [
                'nome' => 'Medico',
                'sobrenome' => 'Medico',
                'nome_completo' => 'Medico Medico',
                'email' => 'medico@medico.com',
                'cpf' => '22222222222',
                'perfil_usuario' => 2,
                'status' => 1,
            ],
            [
                'nome' => 'Secretaria',
                'sobrenome' => 'Secretaria',
                'nome_completo' => 'Secretaria Secretaria',
                'email' => 'secretaria@secretaria.com',
                'cpf' => '33333333333',
                'perfil_usuario' => 3,
                'status' => 1,
            ],
        ]);
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
