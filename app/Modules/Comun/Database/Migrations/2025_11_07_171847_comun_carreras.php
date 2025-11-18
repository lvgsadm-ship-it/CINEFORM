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
        Schema::create('comun_carreras', function (Blueprint $table) {
            $table->id('id_carrera'); // "id_carrera" PRIMARY KEY
            $table->string('nombre_carrera')->nullable(); // "nombre_carrera" varchar
            $table->string('descripcion')->nullable(); // "descripcion" varchar
            $table->string('status')->nullable(); // "status" varchar
            
            // Columnas de auditoría
            $table->integer('creado_por')->nullable();
            $table->timestamp('creado_en')->nullable();
            $table->integer('actualizado_por')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comun_carreras');
    }
};