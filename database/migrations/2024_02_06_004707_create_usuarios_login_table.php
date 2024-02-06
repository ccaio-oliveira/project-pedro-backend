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
        Schema::create('usuarios_login', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('usuario_id');
            $table->dateTime('ultimo_acesso')->nullable();
            $table->rememberToken();
            $table->dateTime('updated_at')->nullable();

            $table->foreign('usuario_id')->references('id')->on('usuarios');

            $table->engine = 'InnoDB';
        });

        DB::table('usuarios_login')->insert([
            [
                'email' => 'admin@admin.com',
                'password' => '$2y$12$aTWoGK9VhkkcfZz9ZVXfouvCtIVtn8m3WkLo21dHu4pY9Pn8xIZl2',
                'usuario_id' => 1,
            ],
            [
                'email' => 'medico@medico.com',
                'password' => '$2y$12$BL4l3HxJeS9KJhipketJh.LBWgeWyDUU.4DaV7.QepxTmK1Ubun0K',
                'usuario_id' => 2,
            ],
            [
                'email' => 'secretaria@secretaria.com',
                'password' => '$2y$12$451eX3GBYeRqh.GRdqxKd.rlnqXE5kG0ixGLkX5UQg8fW51BH2lrW',
                'usuario_id' => 3,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios_login', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
        });

        Schema::dropIfExists('usuarios_login');
    }
};
