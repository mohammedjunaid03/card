<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Basic details
            $table->string('name');
            $table->date('date_of_birth');
            $table->integer('age')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->text('address')->nullable();

            // Medical details
            $table->enum('blood_group', [
                'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'
            ])->nullable();

            // Aadhaar & photo uploads
            $table->string('aadhaar_path')->nullable(); // encrypted or secured file path
            $table->string('photo_path')->nullable();   // user profile photo

            // Contact & login
            $table->string('email')->unique();
            $table->string('mobile', 15)->unique();
            $table->string('password');

            // Verification flags
            $table->boolean('email_verified')->default(false);
            $table->boolean('mobile_verified')->default(false);

            // Role-based access
            $table->enum('role', ['user', 'hospital', 'staff', 'admin'])->default('user');

            // Account status
            $table->enum('status', ['pending', 'active', 'blocked'])->default('pending');

            // Security helpers
            $table->rememberToken();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
