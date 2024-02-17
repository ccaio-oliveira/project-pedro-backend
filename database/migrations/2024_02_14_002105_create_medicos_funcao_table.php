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
        Schema::create('medicos_funcao', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('funcao');

            $table->engine = 'InnoDB';
        });

        $specialties = [
            'Anestesiologia',
            'Cardiologia',
            'Cirurgia Geral',
            'Cirurgia Plástica',
            'Cirurgia Vascular',
            'Clínica Médica',
            'Dermatologia',
            'Endocrinologia',
            'Gastroenterologia',
            'Geriatria',
            'Ginecologia',
            'Hematologia',
            'Infectologia',
            'Nefrologia',
            'Neurologia',
            'Nutrologia',
            'Oftalmologia',
            'Oncologia',
            'Ortopedia',
            'Otorrinolaringologia',
            'Pediatria',
            'Pneumologia',
            'Proctologia',
            'Psiquiatria',
            'Radiologia',
            'Reumatologia',
            'Urologia'
        ];

        foreach ($specialties as $specialty) {
            DB::table('medicos_funcao')->insert([
                'funcao' => $specialty,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicos_funcao');
    }
};
