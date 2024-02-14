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
        Schema::create('medicos_funcao', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('funcao');
            $table->integer('medico_id');

            $table->foreign('medico_id')->references('id')->on('usuarios');

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicos_funcao');
    }
};
