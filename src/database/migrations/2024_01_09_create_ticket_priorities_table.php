<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_priorities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('color');
            $table->integer('level')->unique();
            $table->integer('response_time')->nullable(); // in hours
            $table->integer('resolution_time')->nullable(); // in hours
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_priorities');
    }
};
