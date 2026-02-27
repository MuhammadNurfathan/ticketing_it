<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pending', function (Blueprint $table) {
            // apakah duration pending ini dihitung untuk effective_end_date?
            $table->boolean('count_to_effective')->default(true)->after('duration_minutes');

            // opsional: kalau mau input manual, bisa simpan value override
            $table->integer('duration_override')->nullable()->after('count_to_effective');
        });
    }

    public function down(): void
    {
        Schema::table('pending', function (Blueprint $table) {
            $table->dropColumn(['count_to_effective', 'duration_override']);
        });
    }
};