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
            $table->string('nome_completo');
            $table->string('apelido')->nullable();
            $table->integer('foto_id')->default(0);
            $table->integer('especialidade')->default(0);
            $table->string('email', 50)->unique();
            $table->string('instituicao');
            $table->integer('perfil_usuario');
            $table->integer('status')->default(0);
            $table->timestamps();

            $table->foreign('perfil_usuario')->references('id')->on('perfil');
            $table->foreign('especialidade')->references('id')->on('medicos_funcao');

            $table->engine = 'InnoDB';
        });

        DB::table('usuarios')->insert([
            [
                'nome_completo' => 'Admin Admin',
                'email' => 'admin@admin.com',
                'perfil_usuario' => 1,
                'especialidade' => 0,
                'status' => 1,
                'instituicao' => "Hospital Geral"
            ],
            [
                'nome_completo' => 'Medico Medico',
                'email' => 'medico@medico.com',
                'perfil_usuario' => 2,
                'status' => 1,
                'especialidade' => 10,
                'instituicao' => "Hospital Geral"
            ],
            [
                'nome_completo' => 'Secretaria Secretaria',
                'email' => 'secretaria@secretaria.com',
                'perfil_usuario' => 3,
                'especialidade' => 0,
                'status' => 1,
                'instituicao' => "Hospital Geral"
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
