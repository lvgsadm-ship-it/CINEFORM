<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('security_processes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->text('description');
            $table->string('icon', 50);
            $table->string('route', 50);
            $table->string('actions', 200);
            $table->integer('order');
            $table->boolean('active');
            $table->unsignedBigInteger('menu_id');
            $table->foreign('menu_id')->references('id')->on('security_menus');
        });

        DB::table('security_processes')->insert([
            //1
            [
                'name' => 'Profiles',
                'description' => 'Profiles',
                'icon' => 'fas fa-user-friends',
                'route' => 'profiles',
                "actions" => 'create|edit|permissions',
                'order' => '1',
                'active' => true,
                'menu_id' => 1,
            ],
            [
                'name' => 'Users',
                'description' => 'Users',
                'icon' => 'fas fa-user',
                'route' => 'users',
                "actions" => 'create|edit|security',
                'order' => '2',
                'active' => true,
                'menu_id' => 1,
            ]

        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_processes');
    }
};
