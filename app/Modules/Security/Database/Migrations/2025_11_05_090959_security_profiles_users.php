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
       Schema::create('security_profiles_users', function (Blueprint $table) {
            $table->id('id_rol_persona');
            $table->foreignId('id_rol')->constrained('security_profiles')->onDelete('cascade');            
            $table->foreignId('id_users')->constrained('security_users')->onDelete('cascade');                             
            $table->unsignedBigInteger('status')->default(0);
            $table->date('fecha_aprobacion')->nullable();
            $table->unsignedBigInteger('aprobado_por')->nullable();
            $table->unsignedBigInteger('creado_por');
            $table->dateTime('creado_en');
            $table->unsignedBigInteger('actualizado_por')->nullable();
            $table->dateTime('actualizado_en')->nullable();
        });

        DB::table('security_profiles_users')->insert([
            ['id_rol_persona' => 1,'id_rol'=> 1, 'id_users' => 1,'creado_por' => 1, 'creado_en' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('security_profiles_users');
    }
};
