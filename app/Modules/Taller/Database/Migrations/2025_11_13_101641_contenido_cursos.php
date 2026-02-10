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
        Schema::create('taller_contenido_cursos', function (Blueprint $table) {
            $table->id('id_contenido_curso');
            $table->unsignedBigInteger('id_curso');
            $table->string('titulo');
            $table->text('descripcion_breve');
            $table->text('descripcion');
            $table->string('url_contenido');
            $table->boolean('es_evaluacion')->default(false);
            $table->unsignedBigInteger('id_tipo_evaluacion')->nullable();
            $table->decimal('ponderacion', 5, 2)->nullable();
            $table->integer('orden');
            $table->unsignedBigInteger('creado_por');
            $table->timestamp('creado_en')->useCurrent();
            $table->unsignedBigInteger('actualizado_por')->nullable();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });


        DB::table('taller_contenido_cursos')->insert([
            [
                'id_curso' => 1,
                'titulo' => 'Tipos de lentes y Cámaras',
                'descripcion_breve' => 'Tipos de lentes, Cámaras y situaciones en las que deben ser utilizados',
                'descripcion' => 'Tipos de lentes, Cámaras y situaciones en las que deben ser utilizados',
                'es_evaluacion' => true,
                'ponderacion' => 50,
                'id_tipo_evaluacion' => 1,
                'url_contenido' => 'https://www.youtube.com/watch?v=123456789',
                'orden' => 1,
                'creado_por' => 1,
                'creado_en' => now(),
                'actualizado_por' => 1,
                'actualizado_en' => now(),
            ],
            [
                'id_curso' => 1,
                'titulo' => 'Tipos de iluminacion',
                'descripcion_breve' => 'Tipos de iluminacion y situaciones en las que deben ser utilizados',
                'descripcion' => 'Tipos de iluminacion y situaciones en las que deben ser utilizados',
                'es_evaluacion' => true,
                'ponderacion' => 50,
                'id_tipo_evaluacion' => 1,
                'url_contenido' => 'https://www.youtube.com/watch?v=987654321',
                'orden' => 2,
                'creado_por' => 1,
                'creado_en' => now(),
                'actualizado_por' => 1,
                'actualizado_en' => now(),
            ],
            [
                'id_curso' => 2,
                'titulo' => 'Que es el cine ?',
                'descripcion_breve' => 'Conceptualización del cine y manejo de conceptos basicos',
                'descripcion' => 'Conceptualización del cine y manejo de conceptos basicos',
                'es_evaluacion' => true,
                'ponderacion' => 50,
                'id_tipo_evaluacion' => 1,
                'url_contenido' => 'https://www.youtube.com/watch?v=123456789',
                'orden' => 1,
                'creado_por' => 1,
                'creado_en' => now(),
                'actualizado_por' => 1,
                'actualizado_en' => now(),
            ],
            [
                'id_curso' => 2,
                'titulo' => 'Tipos de Cine',
                'descripcion_breve' => 'Manejo de las variaciones del cine',
                'descripcion' => 'Manejo de las variaciones del cine',
                'es_evaluacion' => true,
                'ponderacion' => 50,
                'id_tipo_evaluacion' => 1,
                'url_contenido' => 'https://www.youtube.com/watch?v=123456789',
                'orden' => 2,
                'creado_por' => 1,
                'creado_en' => now(),
                'actualizado_por' => 1,
                'actualizado_en' => now(),
            ],
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taller_contenido_cursos');
    }
};
