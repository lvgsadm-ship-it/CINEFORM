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
        Schema::create('security_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('description');
            $table->string('icon', 50);
            $table->integer('order');
            $table->boolean('active');
            $table->unsignedBigInteger('module_id');
            $table->foreign('module_id')->references('id')->on('security_modules');
        });

        DB::table('security_menus')->insert([
            [ //1
                "name" => "Security",
                "description" => "",
                "icon" => "fas fa-user-shield",
                "order" => 1,
                'active' => true,
                "module_id" => 1
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_menus');
    }
};
