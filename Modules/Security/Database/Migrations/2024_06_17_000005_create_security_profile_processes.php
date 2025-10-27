<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('security_profile_processes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('process_id');
            $table->unsignedBigInteger('profile_id');
            $table->string('actions', 200)->nullable();

            $table->foreign('process_id')->references('id')->on('security_processes');
            $table->foreign('profile_id')->references('id')->on('security_profiles');
        });

        $sql = "
                INSERT INTO security_profile_processes(process_id, profile_id, actions)
                SELECT id, 1, actions
                FROM security_processes
                ";
        DB::insert($sql);
        
		/*
        DB::table('security_profile_processes')->insert([
             
             [
                 'process_id' => '4',
                 'profile_id' => '4',
                 'actions' => '*',
             ]
         ]);
		 */
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('security_profile_processes');
    }
};
