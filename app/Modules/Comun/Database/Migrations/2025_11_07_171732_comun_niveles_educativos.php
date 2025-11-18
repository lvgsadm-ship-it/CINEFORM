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
        Schema::create('comun_niveles_educativos', function (Blueprint $table) {
            $table->id('id_nivel_educativo'); // "id_nivel_educativo" PRIMARY KEY
            $table->string('nivel')->nullable(); // "nivel" varchar
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
        Schema::dropIfExists('comun_niveles_educativos');
    }
};