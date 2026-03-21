<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // Waiting, In Progress, Done
            $table->string('type');   
            $table->string('context');       // ticket, project
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status');
    }
};
