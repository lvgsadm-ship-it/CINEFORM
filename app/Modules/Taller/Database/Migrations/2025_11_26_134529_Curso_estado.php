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
        Schema::create('curso_estado', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_curso');
            $table->unsignedBigInteger('id_estado');
            $table->text('motivo')->nullable();
            $table->timestamps();

            $table->foreign('id_curso')->references('id_curso')->on('taller_cursos')->onDelete('cascade');
            $table->foreign('id_estado')->references('id_estado')->on('estados')->onDelete('cascade');
        });

        // Insertar registros iniciales si la tabla estados existe
        DB::table('curso_estado')->insert([
            [
                'id_curso' => 1,
                'id_estado' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_curso' => 2,
                'id_estado' => 1,
                'created_at' => now(),
                'updated_at' => now(),
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
        Schema::dropIfExists('curso_estado');
    }
};
