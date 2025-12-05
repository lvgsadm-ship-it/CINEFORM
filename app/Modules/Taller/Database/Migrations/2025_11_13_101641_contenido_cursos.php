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
            $table->text('descripcion_breve');
            $table->text('descripcion');
          //  $table->string('tipo_contenido');
           $table->string('url_contenido');
            $table->integer('orden');
            $table->unsignedBigInteger('creado_por');
            $table->timestamp('creado_en')->useCurrent();
            $table->unsignedBigInteger('actualizado_por')->nullable();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });


        DB::table('taller_contenido_cursos')->insert([
            [
                'id_curso' => 1,
                'titulo' => 'Contenido 1',
                'descripcion_breve' => 'Descripción breve del contenido 1',
                'descripcion' => 'Descripción del contenido 1',
              //  'tipo_contenido' => 'Video', 
              
                'url_contenido' => 'https://www.youtube.com/watch?v=123456789',
                'orden' => 1,
                'creado_por' => 1,
                'creado_en' => now(),
                'actualizado_por' => 1,
                'actualizado_en' => now(),
            ],
            [
                'id_curso' => 1,
                'titulo' => 'Contenido 2',
                'descripcion_breve' => 'Descripción breve del contenido 2',
                'descripcion' => 'Descripción del contenido 2',
               // 'tipo_contenido' => 'Video',
                'url_contenido' => 'https://www.youtube.com/watch?v=987654321',
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
