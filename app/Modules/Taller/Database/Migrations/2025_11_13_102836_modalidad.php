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
        Schema::create('modalidad', function (Blueprint $table) {
            $table->id('id_modalidad');
            $table->string('nombre_modalidad');
            $table->text('descripcion');
            $table->string('status');
            $table->string('creado_por');
            $table->timestamp('creado_en')->useCurrent();
            $table->string('actualizado_por')->nullable();
            $table->timestamp('actualizado_en')->nullable()->useCurrentOnUpdate();
            $table->timestamps();
        });

        DB::table('modalidad')->insert([
            [
                'nombre_modalidad' => 'Presencial',
                'descripcion' => 'Modalidad presencial',
                'status' => 'Activo',
                'creado_por' => 'Usuario 1',
                'creado_en' => now(),
                'actualizado_por' => 'Usuario 1',
                'actualizado_en' => now(),
            ],
            [
                'nombre_modalidad' => 'Virtual',
                'descripcion' => 'Modalidad virtual',
                'status' => 'Activo',
                'creado_por' => 'Usuario 2',
                'creado_en' => now(),
                'actualizado_por' => 'Usuario 2',
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
        //
    }
};
