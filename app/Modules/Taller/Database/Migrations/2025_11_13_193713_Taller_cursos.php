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
        Schema::create('taller_cursos', function (Blueprint $table) {
            $table->id('id_curso');
            $table->string('nombre');
            $table->unsignedBigInteger('id_modalidad')->nullable();;//agregado para reemplazar tabla modadlidad_cursos
           
            $table->unsignedBigInteger('id_persona');//agregado para reemplazar tabla cursos_docentes
            $table->string('descripcion')->nullable();
            $table->integer('duracion')->nullable(); 
            $table->integer('horas')->nullable();
           // $table->string('cantidad_cupos')->nullable();
           // $table->string('status')->nullable();
           // $table->string('creado_por')->nullable();
        $table->timestamp('creado_en')->useCurrent();
           // $table->string('actualizado_por')->nullable();
           // $table->timestamp('actualizado_en')->nullable()->useCurrentOnUpdate();
           // $table->timestamps();

           
        });

            DB::table('taller_cursos')->insert([    
            [
                'nombre' => 'Puerba',
                'id_modalidad' => 1,
                'id_persona' => 2,
                'duracion' => 5,
                'horas' => 5,
                'descripcion' => 'Prueba',
                'creado_en' => now(),
            ],
             [
                'nombre' => 'Puerba2',
                'id_modalidad' => 1,
                'id_persona' => 1,
                'duracion' => 5,
                'horas' => 5,
                'descripcion' => 'Prueba',  
                'creado_en' => now(),
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
        Schema::dropIfExists('taller_cursos');
    }
};