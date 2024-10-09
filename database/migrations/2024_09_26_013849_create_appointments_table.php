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
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tittle');
            $table->text('description');
            $table->string('color', 7);
            $table->string('text_color', 7);
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->enum('status', ['pending', 'started', 'canceled', 'ended'])->default('pending');
            $table->foreignUuid('client_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
