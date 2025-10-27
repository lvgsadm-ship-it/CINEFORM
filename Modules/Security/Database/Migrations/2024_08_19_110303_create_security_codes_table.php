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
    public function up() {
        Schema::create('security_codes', function (Blueprint $table) {
            $table->id();
            $table->string('email', 50)->unique();
            $table->string('code');
            $table->timestamp('date');
            $table->boolean('processed')->default(false);
        });
        DB::table('security_codes')->insert([
            [
               "email"=>"miguelrivero@inac.gob.ve", 
               "code"=>"666", 
               "date"=>now(), 
               "processed"=>false
            ]
        ]);    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('security_codes');
    }
};
