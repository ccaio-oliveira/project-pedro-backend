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
        Schema::create('perfil', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nome');

            $table->engine = 'InnoDB';
        });

        DB::table('perfil')->insert([
            ['nome' => 'Administrador'],
            ['nome' => 'Medico'],
            ['nome' => 'Secretaria'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfil');
    }
};
