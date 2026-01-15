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
        Schema::table('taller_contenido_cursos', function (Blueprint $table) {
            $table->boolean('es_evaluacion')->default(false)->after('url_contenido');
            $table->unsignedBigInteger('id_tipo_evaluacion')->nullable()->after('es_evaluacion');
            $table->decimal('ponderacion', 5, 2)->nullable()->after('id_tipo_evaluacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taller_contenido_cursos', function (Blueprint $table) {
            $table->dropColumn(['es_evaluacion', 'id_tipo_evaluacion', 'ponderacion']);
        });
    }
};
