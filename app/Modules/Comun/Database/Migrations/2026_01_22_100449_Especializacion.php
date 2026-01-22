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
        Schema::create('especializaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('status');
            $table->integer('creado_por');
            $table->dateTime('creado_en');
            $table->integer('actualizado_por');
            $table->dateTime('actualizado_en');
        });

        DB::table('especializaciones')->insert([
            [
                'nombre' => 'Cinematografia',
                'descripcion' => 'Especializacion en cinematografia',
                'status' => 'Activo',
                'creado_por' => 1,
                'creado_en' => now(),
                'actualizado_por' => 1,
                'actualizado_en' => now(),
            ],
            [
                'nombre' => 'Edicion',
                'descripcion' => 'Especializacion en edicion',
                'status' => 'Activo',
                'creado_por' => 1,
                'creado_en' => now(),
                'actualizado_por' => 1,
                'actualizado_en' => now(),
            ],
            [
                'nombre' => 'Iluminacion',
                'descripcion' => 'Especializacion en iluminacion',
                'status' => 'Activo',
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
        Schema::dropIfExists('especializaciones');
    }
};
