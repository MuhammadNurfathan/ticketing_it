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

            // kode tiket unik
            $table->string('ticket_code', 10)->unique();

            // relasi user (pelapor)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            // relasi user support
            $table->foreignId('support_id')
                ->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            // relasi ke kategori, inventaris, status, dan prioritas
            $table->foreignId('problem_category_id')
                ->nullable()
                ->constrained('problem_categories')
                ->nullOnDelete();

            $table->foreignId('assets_id')
                ->nullable()
                ->constrained('assets')
                ->nullOnDelete();

            $table->foreignId('status_id')
                ->nullable()
                ->constrained('status')
                ->nullOnDelete();

            $table->foreignId('priority_id')
                ->nullable()
                ->constrained('priority')
                ->nullOnDelete();

            // detail tiket
            $table->text('problem')->nullable();

            // path gambar disimpan di database (file fisiknya di folder storage)
            $table->string('image', 255)->nullable();

            $table->text('solution')->nullable();
            $table->text('notes')->nullable();

            // waktu dan durasi
            $table->dateTime('request_date')->nullable();
            $table->integer('waiting_hour')->nullable(); // perhatikan plural: hours
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('time_spent')->nullable();

            // timestamps dan soft delete
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
