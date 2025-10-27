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
        Schema::create('security_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('description');
            $table->boolean('active');
            
            $table->unsignedBigInteger('user_id');
            $table->timestamp('register_date');
            $table->ipAddress('ip');
        });

        DB::table('security_profiles')->insert([
            [
                'name' => 'Administrator',
                'description' => 'Administrator',
                'active' => true,
                'user_id' => 1,
                'register_date'=>now(),
                'ip' => '127.0.0.1',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('security_profiles');
    }
};
