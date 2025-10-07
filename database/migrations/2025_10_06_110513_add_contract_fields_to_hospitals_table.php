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
        Schema::table('hospitals', function (Blueprint $table) {
            $table->date('contract_start_date')->nullable()->after('status');
            $table->date('contract_end_date')->nullable()->after('contract_start_date');
            $table->string('contract_document_path')->nullable()->after('contract_end_date');
            $table->enum('contract_status', ['active', 'expired', 'terminated', 'pending_renewal'])->default('active')->after('contract_document_path');
            $table->text('contract_notes')->nullable()->after('contract_status');
            $table->timestamp('contract_created_at')->nullable()->after('contract_notes');
            $table->timestamp('contract_updated_at')->nullable()->after('contract_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospitals', function (Blueprint $table) {
            $table->dropColumn([
                'contract_start_date',
                'contract_end_date', 
                'contract_document_path',
                'contract_status',
                'contract_notes',
                'contract_created_at',
                'contract_updated_at'
            ]);
        });
    }
};
