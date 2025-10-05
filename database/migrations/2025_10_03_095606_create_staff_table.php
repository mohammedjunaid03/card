<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();

            // Foreign key: Each staff belongs to one hospital
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');

            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile', 15);

            $table->string('password');

            // Staff role in hospital
            $table->enum('role', [
                'doctor',
                'nurse',
                'receptionist',
                'lab_technician',
                'admin_staff',
                'other'
            ])->default('other');

            // Status of staff
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('staff');
    }
};
