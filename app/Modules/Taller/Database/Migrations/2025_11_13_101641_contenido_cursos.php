<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taller_contenido_cursos', function (Blueprint $table) {
            $table->id('id_contenido_curso');
            $table->unsignedBigInteger('id_curso');
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('tipo_contenido');
            $table->string('url_contenido');
            $table->integer('orden');
        // $table->unsignedBigInteger('creado_por');
        //  $table->timestamp('creado_en')->useCurrent();
        //  $table->unsignedBigInteger('actualizado_por');
          //  $table->timestamp('actualizado_en')->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
