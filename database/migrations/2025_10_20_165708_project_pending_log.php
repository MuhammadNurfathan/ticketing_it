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
        Schema::create('project_pending_log', function (Blueprint $table) {
        $table->id();
        $table->foreignId('project_header_id')->constrained('project_header')->onUpdate('cascade')->onDelete('cascade');
        $table->datetime('pending_start')->nullable();
        $table->datetime('pending_end')->nullable();
        $table->integer('pending_duration')->default(0); 
        $table->string('reason', 255)->nullable();
        $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::dropIfExists('project_pending_logs');
    }
};
