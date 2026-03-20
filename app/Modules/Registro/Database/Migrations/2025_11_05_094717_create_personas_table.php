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
        Schema::create('comun.personas', function (Blueprint $table) {
            $table->id('id_persona');
            $table->foreignId('user_id')->constrained('security_users')->onDelete('cascade');
            $table->foreignId('tipo_dni')->constrained('public.security_document_types');
            $table->string('dni')->nullable();
            $table->string('pasaporte')->nullable();
            $table->string('rif')->nullable();
            $table->string('reg_nac_cine')->nullable();
            $table->foreignId('genero')->constrained('public.security_genders');
            $table->string('primer_nombre')->nullable();
            $table->string('segundo_nombre')->nullable();
            $table->string('primer_apellido')->nullable();
            $table->string('segundo_apellido')->nullable();
            $table->string('telefono')->nullable();
            $table->string('telefono_opcional')->nullable();
            $table->foreignId('id_pais')->constrained('public.security_countries');
            $table->foreignId('id_estado')->reference('id_estado')->on('comun.estados');
            $table->foreignId('id_municipio')->reference('id_municipio')->on('comun.municipios');
            $table->foreignId('id_parroquia')->reference('id_parroquia')->on('comun.parroquias');
            $table->string('direccion')->nullable();
            $table->integer('creado_por')->nullable();
            $table->timestamp('creado_en')->nullable();
            $table->integer('actualizado_por')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });

        DB::table('comun.personas')->insert([
            [
                'user_id' => 1,
                'tipo_dni' => 1,
                'dni' => '14587567',
                'genero' => 2,
                'primer_nombre' => 'Liliana',
                'segundo_nombre' => 'Liliana',
                'primer_apellido' => 'Guerra',
                'segundo_apellido' => 'Guerra',
                'telefono' => '04123673871',
                'id_pais' => 238,
                'id_estado' => 24,
                'id_municipio' => 254,
                'id_parroquia' => 38,
                'direccion' => 'ALGUN LUGAR',
                'creado_por' => 1,
                'creado_en' => now()
            ],
            [
                'user_id' => 2,
                'tipo_dni' => 1,
                'dni' => '99999999',
                'genero' => 2,
                'primer_nombre' => 'admin',
                'segundo_nombre' => 'admin',
                'primer_apellido' => 'admin',
                'segundo_apellido' => 'admin',
                'telefono' => '04123673871',
                'id_pais' => 238,
                'id_estado' => 24,
                'id_municipio' => 254,
                'id_parroquia' => 38,
                'direccion' => 'ALGUN LUGAR',
                'creado_por' => 1,
                'creado_en' => now(),

            ],
            [
                'user_id' => 3,
                'tipo_dni' => 1,
                'dni' => '30098588',
                'genero' => 2,
                'primer_nombre' => 'Cristhofer',
                'segundo_nombre' => 'Luis',
                'primer_apellido' => 'Leon',
                'segundo_apellido' => 'Gonzalez',
                'telefono' => '04123673871',
                'id_pais' => 238,
                'id_estado' => 24,
                'id_municipio' => 254,
                'id_parroquia' => 38,
                'direccion' => 'ALGUN LUGAR',
                'creado_por' => 1,
                'creado_en' => now(),

            ],
            [
                'user_id' => 4,
                'tipo_dni' => 1,
                'dni' => '29310488',
                'genero' => 2,
                'primer_nombre' => 'Zoe',
                'segundo_nombre' => 'Anais',
                'primer_apellido' => 'Medina',
                'segundo_apellido' => 'Quintero',
                'telefono' => '04123673871',
                'id_pais' => 238,
                'id_estado' => 24,
                'id_municipio' => 254,
                'id_parroquia' => 38,
                'direccion' => 'ALGUN LUGAR',
                'creado_por' => 1,
                'creado_en' => now(),

            ],
            [
                'user_id' => 5,
                'tipo_dni' => 1,
                'dni' => '12345678',
                'genero' => 2,
                'primer_nombre' => 'Pedro',
                'segundo_nombre' => 'Julian',
                'primer_apellido' => 'Leon',
                'segundo_apellido' => 'Gonzales',
                'telefono' => '04123673871',
                'id_pais' => 238,
                'id_estado' => 24,
                'id_municipio' => 254,
                'id_parroquia' => 38,
                'direccion' => 'ALGUN LUGAR',
                'creado_por' => 1,
                'creado_en' => now(),

            ],
            [
                'user_id' => 6,
                'tipo_dni' => 1,
                'dni' => '12345679',
                'genero' => 2,
                'primer_nombre' => 'Jose',
                'segundo_nombre' => 'Luis',
                'primer_apellido' => 'Garcia',
                'segundo_apellido' => '',
                'telefono' => '04123673871',
                'id_pais' => 238,
                'id_estado' => 24,
                'id_municipio' => 254,
                'id_parroquia' => 38,
                'direccion' => 'ALGUN LUGAR',
                'creado_por' => 1,
                'creado_en' => now(),

            ],
            [
                'user_id' => 7,
                'tipo_dni' => 1,
                'dni' => '12345680',
                'genero' => 2,
                'primer_nombre' => 'Maria',
                'segundo_nombre' => 'Julian',
                'primer_apellido' => 'Leon',
                'segundo_apellido' => 'Gonzales',
                'telefono' => '04123673871',
                'id_pais' => 238,
                'id_estado' => 24,
                'id_municipio' => 254,
                'id_parroquia' => 38,
                'direccion' => 'ALGUN LUGAR',
                'creado_por' => 1,
                'creado_en' => now(),

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
        Schema::dropIfExists('personas');
    }
};
