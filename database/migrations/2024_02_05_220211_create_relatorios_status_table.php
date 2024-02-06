'<?php

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
        Schema::create('relatorios_status', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nome');

            $table->engine = 'InnoDB';
        });

        DB::table('relatorios_status')->insert([
            ['nome' => 'Pendente'],
            ['nome' => 'Visualizado'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relatorios_status');
    }
};
