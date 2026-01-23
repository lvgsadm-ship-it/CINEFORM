<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_curso')->nullable();
            $table->unsignedBigInteger('id_tipo_evaluacion')->nullable();
            $table->date('fecha_evaluacion');
            $table->string('status');
            $table->string('creado_por');
            $table->timestamp('creado_en')->useCurrent();
            $table->string('actualizado_por')->nullable();
            $table->timestamp('actualizado_en')->nullable()->useCurrentOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluaciones');
    }
};
    