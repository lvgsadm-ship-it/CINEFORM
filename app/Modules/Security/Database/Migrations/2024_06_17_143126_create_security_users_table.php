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
            $table->id(); // id int [pk, increment]
            $table->string('username', 300)->unique()->notNullable(); // username varchar(300) unique not null
            $table->string('email', 300)->unique()->notNullable(); // username varchar(300) unique not null
            $table->string('password'); // password varchar not null
            $table->boolean('change_password')->default(false)->notNullable();
            $table->string('token', 50)->default('')->notNullable();
            $table->timestamp('date_change_password')->nullable();
            $table->timestamp('register_date')->notNullable();
            $table->unsignedBigInteger('active')->default(0);
            $table->string('ip', 45)->notNullable(); // ip varchar(45)        
            
        });

        DB::table('security_users')->insert([
            [
                'username' => 'lvgs',
                'email' => 'lvgsadm@gmail.com',
                'password' => Hash::make('123'),   
                'change_password' => false,                
                'id' => 1,
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
