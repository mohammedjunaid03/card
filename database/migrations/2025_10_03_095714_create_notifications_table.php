<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Polymorphic relation (recipient can be user, hospital, staff, admin)
            $table->string('recipient_type'); 
            $table->unsignedBigInteger('recipient_id')->nullable();

            $table->string('title');
            $table->text('message')->nullable();

            // Optional: allow linking notifications to specific models (audit, card, etc.)
            $table->string('notifiable_type')->nullable();
            $table->unsignedBigInteger('notifiable_id')->nullable();

            $table->enum('type', ['info', 'warning', 'success', 'error'])->default('info');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index(['recipient_type', 'recipient_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
