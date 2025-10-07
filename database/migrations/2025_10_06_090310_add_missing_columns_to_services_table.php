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
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'hospital_id')) {
                $table->foreignId('hospital_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('services', 'discount_percentage')) {
                $table->decimal('discount_percentage', 5, 2)->default(0)->after('description');
            }
            if (!Schema::hasColumn('services', 'price')) {
                $table->decimal('price', 10, 2)->default(0)->after('discount_percentage');
            }
            if (!Schema::hasColumn('services', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'hospital_id')) {
                $table->dropForeign(['hospital_id']);
                $table->dropColumn('hospital_id');
            }
            if (Schema::hasColumn('services', 'discount_percentage')) {
                $table->dropColumn('discount_percentage');
            }
            if (Schema::hasColumn('services', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('services', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};