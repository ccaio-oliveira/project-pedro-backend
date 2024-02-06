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
        Schema::create('grau_prioridade', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('grau');

            $table->engine = 'InnoDB';
        });

        DB::table('grau_prioridade')->insert([
            ['grau' => 'prioridade'],
            ['grau' => 'nao_urgente'],
            ['grau' => 'rotina'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grau_prioridade');
    }
};
