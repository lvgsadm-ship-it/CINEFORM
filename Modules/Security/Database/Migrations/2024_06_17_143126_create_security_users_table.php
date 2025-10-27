<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('security_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_type_id');
            $table->string('document', 20)->nullable();
            $table->string('full_name', 200)->nullable();;
            $table->string('email', 150)->unique();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            
            $table->unsignedBigInteger('profile_id');
            $table->boolean('active')->default(true);
            
            $table->boolean('change_password')->default(false);
            $table->string('token', 50)->default('');
            $table->timestamp('date_change_password')->nullable();
            
            
            
            $table->unsignedBigInteger('user_id')->nullable();
            
            $table->timestamp('register_date');
            $table->ipAddress('ip');
            
            
            
            $table->foreign('document_type_id')->references('id')->on('security_document_types');
            $table->foreign('profile_id')->references('id')->on('security_profiles');
            $table->foreign('country_id')->references('id')->on('security_countries');
            
        });

        DB::table('security_users')->insert([
            [
                'document' => '99999999',
                'document_type_id' => 1,
                'full_name' => 'admin',
                'email' => 'admib@mail.com',
                'username' => 'admin',
                'password' => Hash::make('12345678'),
                'phone' => '+58 412-7777777',
                
                
                'profile_id' => 1,
                'country_id' => 238,
                'active' => true,
                'change_password' => false,
                
                'user_id' => 1,
                'ip' => '127.0.0.1',
                'register_date'=>now()

            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_users');
    }
};
