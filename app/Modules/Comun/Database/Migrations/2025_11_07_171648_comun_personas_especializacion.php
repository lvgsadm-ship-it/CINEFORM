<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comun_personas_especializacion', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('id_persona')->nullable(); // "id_persona" int
            $table->integer('id_especializacion')->nullable(); // "id_especializacion" int
            $table->integer('anos_experiencia')->nullable(); // "anos_experiencia" int

            // Columnas de auditoría
            $table->integer('creado_por')->nullable();
            $table->dateTime('creado_en')->nullable(); // "creado_en" datetime
            $table->integer('actualizado_por')->nullable();
            $table->dateTime('actualizado_en')->nullable(); // "actualizado_en" datetime

            // Llave foránea (se agregará en la migración de llaves foráneas)
            //  $table->foreign('id_persona')->references('id_persona')->on('comun_personas'); 
        });

        DB::table('comun_personas_especializacion')->insert([
            [
                'id_persona' => 1,
                'id_especializacion' => 1,
                'anos_experiencia' => 1,
                'creado_por' => 1,
                'creado_en' => now(),
                'actualizado_por' => 1,
                'actualizado_en' => now(),
            ],
            [
                'id_persona' => 2,
                'id_especializacion' => 2,
                'anos_experiencia' => 1,
                'creado_por' => 1,
                'creado_en' => now(),
                'actualizado_por' => 1,
                'actualizado_en' => now(),
            ],
            [
                'id_persona' => 3,
                'id_especializacion' => 3,
                'anos_experiencia' => 1,
                'creado_por' => 1,
                'creado_en' => now(),
                'actualizado_por' => 1,
                'actualizado_en' => now(),
            ],
            [
                'id_persona' => 4,
                'id_especializacion' => 2,
                'anos_experiencia' => 1,
                'creado_por' => 1,
                'creado_en' => now(),
                'actualizado_por' => 1,
                'actualizado_en' => now(),
            ],
            [
                'id_persona' => 5,
                'id_especializacion' => 3,
                'anos_experiencia' => 1,
                'creado_por' => 1,
                'creado_en' => now(),
                'actualizado_por' => 1,
                'actualizado_en' => now(),
            ],
            [
                'id_persona' => 6,
                'id_especializacion' => 2,
                'anos_experiencia' => 1,
                'creado_por' => 1,
                'creado_en' => now(),
                'actualizado_por' => 1,
                'actualizado_en' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comun_personas_especializacion');
    }
};