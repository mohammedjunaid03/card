<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('mobile', 15)->nullable()->after('email');
            $table->string('role')->default('admin')->after('password');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('role');
            $table->timestamp('email_verified_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['mobile', 'role', 'status', 'email_verified_at']);
        });
    }
};