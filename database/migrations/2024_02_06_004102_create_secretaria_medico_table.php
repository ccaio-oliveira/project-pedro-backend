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
        Schema::create('secretaria_medico', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('secretaria_id');
            $table->integer('medico_id');

            $table->foreign('secretaria_id')->references('id')->on('usuarios');
            $table->foreign('medico_id')->references('id')->on('usuarios');

            $table->engine = 'InnoDB';
        });

        DB::table('secretaria_medico')->insert([
            ['secretaria_id' => 3, 'medico_id' => 2]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secretaria_medico');
    }
};
