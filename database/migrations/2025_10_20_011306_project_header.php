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
        schema::create('project_header', function(Blueprint $table){
            $table -> id();
            $table -> string ('project_code',255)->nullable();
            $table -> string ('project_name',255)->nullable();
            $table -> datetime ('request_date')->nullable();
            $table -> foreignId('requestor_id')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table -> foreignId('dev_id')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table -> foreignId('priority_id')->constrained('priority')->onUpdate('cascade')->onDelete('restrict');
            $table -> foreignId('status_id')->constrained('status')->onUpdate('cascade')->onDelete('restrict');
            $table -> string('description')->nullable();
            $table -> dateTime('progress_date')->nullable();
            $table -> unsignedTinyInteger('progress_percent');
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table -> softDeletes();
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::dropIfExists('project_header');
    }
};
