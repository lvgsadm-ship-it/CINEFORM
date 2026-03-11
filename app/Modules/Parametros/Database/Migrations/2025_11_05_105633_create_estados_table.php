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
       Schema::create('comun.estados', function (Blueprint $table) {
            $table->increments('id_estado');
            $table->string('nombre', 100);
            $table->unsignedInteger('id_pais');
            $table->integer('creado_por')->nullable();
            $table->timestamp('creado_en')->nullable();
            $table->integer('actualizado_por')->nullable();
            $table->timestamp('actualizado_en')->nullable();

            $table->foreign('id_pais')->references('id')->on('public.security_countries')->onDelete('cascade');
        });

         DB::table('comun.estados')->insert([
             ['id_estado' => 1, 'nombre' => 'Amazonas', 'id_pais' => 238],
            ['id_estado' => 2, 'nombre' => 'Anzoátegui', 'id_pais' => 238],
            ['id_estado' => 3, 'nombre' => 'Apure', 'id_pais' => 238],
            ['id_estado' => 4, 'nombre' => 'Aragua', 'id_pais' => 238],
            ['id_estado' => 5, 'nombre' => 'Barinas', 'id_pais' => 238],
            ['id_estado' => 6, 'nombre' => 'Bolívar', 'id_pais' => 238],
            ['id_estado' => 7, 'nombre' => 'Carabobo', 'id_pais' => 238],
            ['id_estado' => 8, 'nombre' => 'Cojedes', 'id_pais' => 238],
            ['id_estado' => 9, 'nombre' => 'Delta Amacuro', 'id_pais' => 238],
            ['id_estado' => 10, 'nombre' => 'Falcón', 'id_pais' => 238],
            ['id_estado' => 11, 'nombre' => 'Guárico', 'id_pais' => 238],
            ['id_estado' => 12, 'nombre' => 'Lara', 'id_pais' => 238],
            ['id_estado' => 13, 'nombre' => 'Mérid_estadoa', 'id_pais' => 238],
            ['id_estado' => 14, 'nombre' => 'Miranda', 'id_pais' => 238],
            ['id_estado' => 15, 'nombre' => 'Monagas', 'id_pais' => 238],
            ['id_estado' => 16, 'nombre' => 'Nueva Esparta', 'id_pais' => 238],
            ['id_estado' => 17, 'nombre' => 'Portuguesa', 'id_pais' => 238],
            ['id_estado' => 18, 'nombre' => 'Sucre', 'id_pais' => 238],
            ['id_estado' => 19, 'nombre' => 'Táchira', 'id_pais' => 238],
            ['id_estado' => 20, 'nombre' => 'Trujillo', 'id_pais' => 238],
            ['id_estado' => 21, 'nombre' => 'La Guaira (antes llamada Vargas)', 'id_pais' => 238],
            ['id_estado' => 22, 'nombre' => 'Yaracuy', 'id_pais' => 238],
            ['id_estado' => 23, 'nombre' => 'Zulia', 'id_pais' => 238],
            ['id_estado' => 24, 'nombre' => 'Distrito Capital', 'id_pais' => 238],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estados');
    }
};
