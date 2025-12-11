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
            Schema::create('tipo_evaluaciones', function (Blueprint $table) {
                $table->id('id_tipo_evaluacion');
                $table->string('nombre');
                $table->string('descripcion');
              //$table->string('status');
                $table->string('creado_por');
                $table->timestamp('creado_en')->useCurrent();
                $table->string('actualizado_por')->nullable();
                $table->timestamp('actualizado_en')->nullable()->useCurrentOnUpdate();
                $table->timestamps();
            });
            DB::table('tipo_evaluaciones')->insert([
            [ 
            'id_tipo_evaluacion' => 1,
            'nombre' => 'Examen',
            'descripcion' => 'Evaluacion escrita sobre un tema en especifico',
            'creado_por' => 1,
            'creado_en' => now(),
            'actualizado_por' => 1,
            'actualizado_en' => now(),
            ],
            [ 
            'id_tipo_evaluacion' => 2,
            'nombre' => 'Exposicion',
            'descripcion' => 'Evaluacion realizando una exposicion sobre un tema en especifico',
            'creado_por' => 1,
            'creado_en' => now(),
            'actualizado_por' => 1,
            'actualizado_en' => now(),
            ],
            [ 
            'id_tipo_evaluacion' => 3,
            'nombre' => 'Trabajo',
            'descripcion' => 'Evaluacion realizando un documento sobre un tema en especifico',
            'creado_por' => 1,
            'creado_en' => now(),
            'actualizado_por' => 1,
            'actualizado_en' => now(),
            ]
            ]);
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
