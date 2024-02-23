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
        Schema::create('usuarios_telefone', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('usuario_id');
            $table->string('telefone', 50);
            $table->string('tipo_telefone', 50)->nullable();
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('usuarios');

            $table->engine = 'InnoDB';
        });

        DB::table('usuarios_telefone')->insert([
            [
                'usuario_id' => 1,
                'telefone' => '5574998059407',
                'tipo_telefone' => 'celular'
            ],
            [
                'usuario_id' => 1,
                'telefone' => '5574998059407',
                'tipo_telefone' => 'whatsapp'
            ],
            [
                'usuario_id' => 2,
                'telefone' => '5574998059407',
                'tipo_telefone' => 'celular'
            ],
            [
                'usuario_id' => 2,
                'telefone' => '5574998059407',
                'tipo_telefone' => 'whatsapp'
            ],
            [
                'usuario_id' => 3,
                'telefone' => '5574998059407',
                'tipo_telefone' => 'celular'
            ],
            [
                'usuario_id' => 3,
                'telefone' => '5574998059407',
                'tipo_telefone' => 'whatsapp'
            ],
        ]);
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
