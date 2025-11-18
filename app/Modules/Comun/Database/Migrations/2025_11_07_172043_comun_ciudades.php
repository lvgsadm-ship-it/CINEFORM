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
        Schema::create('comun_ciudades', function (Blueprint $table) {
            $table->id(); // "id" PRIMARY KEY
            $table->string('nombre', 100)->nullable(); // "nombre" varchar(100)
            $table->unsignedBigInteger('id_estado')->nullable(); // "id_estado" int
            
            // Columnas de auditoría
            $table->integer('creado_por')->nullable();
            $table->timestamp('creado_en')->nullable();
            $table->integer('actualizado_por')->nullable();
            $table->timestamp('actualizado_en')->nullable();

            // Llave foránea (se agregará en la migración de llaves foráneas)
            // $table->foreign('id_estado')->references('id')->on('comun_estados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comun_ciudades');
    }
};