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
        Schema::create('usuarios_telefone', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('usuario_id');
            $table->string('telefone', 50);
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('usuarios');

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios_telefone', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
        });

        Schema::dropIfExists('usuarios_telefone');
    }
};
