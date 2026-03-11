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
        Schema::create('comun.parroquias', function (Blueprint $table) {
            $table->increments('id_parroquia');
            $table->string('nombre', 100);
            $table->unsignedInteger('id_municipio');
            $table->integer('creado_por')->nullable();
            $table->timestamp('creado_en')->nullable();
            $table->integer('actualizado_por')->nullable();
            $table->timestamp('actualizado_en')->nullable();

            $table->foreign('id_municipio')->references('id_municipio')->on('comun.municipios')->onDelete('cascade');
        });

        DB::table('comun.parroquias')->insert([
            ['id_parroquia'  => 1, 'nombre' => 'La Esmeralda', 'id_municipio' => 1],
            ['id_parroquia'  => 2, 'nombre' => 'Huachamacare', 'id_municipio' => 1],
            ['id_parroquia'  => 3, 'nombre' => 'Marawaka', 'id_municipio'	=> 1],
            ['id_parroquia'  => 4, 'nombre' => 'Mavaka', 'id_municipio'	=> 1],
            ['id_parroquia'  => 5, 'nombre' => 'Sierra Parima', 'id_municipio' => 1],
            [ 'id_parroquia' => 6, 'nombre' => 'Atabapo', 'id_municipio' => 2 ],
            [ 'id_parroquia' => 7, 'nombre' =>'Ucata', 'id_municipio' => 2 ],
            [ 'id_parroquia' => 8, 'nombre' =>'Yapacana', 'id_municipio' => 2 ],
            [ 'id_parroquia' => 9, 'nombre' =>'Caname', 'id_municipio' => 2 ],
            [ 'id_parroquia' => 10 , 'nombre' =>'Fernando Girón Tovar', 'id_municipio' => 3 ],
            [ 'id_parroquia' => 11 , 'nombre' =>'Luis Alberto Gómez', 'id_municipio' => 3 ],
            [ 'id_parroquia' => 12 , 'nombre' =>'Parhueña', 'id_municipio' => 3 ],
            [ 'id_parroquia' => 13 , 'nombre' =>'Platanillal', 'id_municipio' => 3 ],
            [ 'id_parroquia' => 14 , 'nombre' =>'Samariapo', 'id_municipio' => 4 ],
            [ 'id_parroquia' => 15 , 'nombre' =>'Sipapo', 'id_municipio' => 4 ],
            [ 'id_parroquia' => 16 , 'nombre' =>'Munduapo', 'id_municipio' => 4 ],
            [ 'id_parroquia' => 17 , 'nombre' =>'Guayapo', 'id_municipio' => 4 ],
            [ 'id_parroquia' => 18 , 'nombre' =>'Isla Ratón', 'id_municipio' => 4 ],
            [ 'id_parroquia' => 19 , 'nombre' =>'Alto Ventuari', 'id_municipio' => 5 ],
            [ 'id_parroquia' => 20 , 'nombre' =>'Medio Ventuari', 'id_municipio' => 5 ],
            [ 'id_parroquia' => 21 , 'nombre' =>'Bajo Ventuari', 'id_municipio' => 5 ],
            [ 'id_parroquia' => 22 , 'nombre' =>'Manapiare', 'id_municipio' => 5 ],
            [ 'id_parroquia' => 23 , 'nombre' =>'Casiquiare', 'id_municipio' => 6 ],
            [ 'id_parroquia' => 24 , 'nombre' =>'Cocuy', 'id_municipio' => 6 ],
            [ 'id_parroquia' => 25 , 'nombre' =>'San Carlos de Río Negro', 'id_municipio' => 6 ],
            [ 'id_parroquia' => 26 , 'nombre' =>'Solano', 'id_municipio' => 6 ],
            [ 'id_parroquia' => 27 , 'nombre' =>'23 de Enero', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 28 , 'nombre' =>'Altagracia', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 29 , 'nombre' =>'Antímano', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 30 , 'nombre' =>'Caricuao', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 31 , 'nombre' =>'Catedral', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 32 , 'nombre' =>'Coche', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 33 , 'nombre' =>'El Junquito', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 34 , 'nombre' =>'El Paraíso', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 35 , 'nombre' =>'El Recreo', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 36 , 'nombre' =>'El Valle', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 37 , 'nombre' =>'Candelaria', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 38 , 'nombre' =>'La Pastora', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 39 , 'nombre' =>'La Vega', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 40 , 'nombre' =>'Macarao', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 41 , 'nombre' =>'San Agustín', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 42 , 'nombre' =>'San Bernardino', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 43 , 'nombre' =>'San José', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 44 , 'nombre' =>'San Juan', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 45 , 'nombre' =>'San Pedro', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 46 , 'nombre' =>'Santa Rosalía', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 47 , 'nombre' =>'Santa Teresa', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 48 , 'nombre' =>'Sucre', 'id_municipio' => 254 ],
            [ 'id_parroquia' => 49 , 'nombre' =>'El Cafetal', 'id_municipio' => 255 ],
            [ 'id_parroquia' => 50 , 'nombre' =>'Las Minas', 'id_municipio' => 255 ],
            [ 'id_parroquia' => 51 , 'nombre' =>'Nuestra Señora del Rosario', 'id_municipio' => 255 ],
            [ 'id_parroquia' => 52 , 'nombre' =>'Chacao', 'id_municipio' => 256 ],
            [ 'id_parroquia' => 53 , 'nombre' =>'Santa Rosalía de Palermo', 'id_municipio' => 257 ],
            [ 'id_parroquia' => 54 , 'nombre' =>'Leoncio Martínez', 'id_municipio' => 258 ],
            [ 'id_parroquia' => 55 , 'nombre' =>'Petare', 'id_municipio' => 258 ],
            [ 'id_parroquia' => 56 , 'nombre' =>'Caucagüita', 'id_municipio' => 258 ],
            [ 'id_parroquia' => 57 , 'nombre' =>'Fila de Mariches', 'id_municipio' => 258 ],
            [ 'id_parroquia' => 58 , 'nombre' =>'La Dolorita', 'id_municipio' => 258 ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parroquias');
    }
};
