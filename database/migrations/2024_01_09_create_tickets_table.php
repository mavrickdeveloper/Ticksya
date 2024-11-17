<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('ticket_number')->unique();
            $table->foreignId('category_id')->constrained('ticket_categories')->cascadeOnDelete();
            $table->foreignId('priority_id')->constrained('ticket_priorities')->cascadeOnDelete();
            $table->foreignId('status_id')->constrained('ticket_statuses')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->timestamp('due_date')->nullable();
            $table->json('tags')->nullable();
            $table->json('attachments')->nullable();
            $table->text('internal_notes')->nullable();
            $table->boolean('is_private')->default(false);
            $table->timestamp('first_response_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
