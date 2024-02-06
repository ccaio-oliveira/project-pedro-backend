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
        Schema::create('medicos_crm', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('crm');
            $table->integer('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuarios');

            $table->engine = 'InnoDB';
        });

        DB::table('medicos_crm')->insert([
            ['crm' => 'CRM/SP 123456', 'usuario_id' => 2]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicos_crm', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
        });

        Schema::dropIfExists('medicos_crm');
    }
};
