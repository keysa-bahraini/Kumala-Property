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
        if (!Schema::hasTable('admin_notifications')) {
            Schema::create('admin_notifications', function (Blueprint $table) {
                $table->id();
                $table->string('type', 30); // 'listing', 'inquiry', 'survey'
                $table->string('title', 150);
                $table->string('message', 255); // Tanpa nomor HP dan email
                $table->string('target_url', 255); // Path internal diawali /admin/
                $table->string('reference_id', 50)->nullable(); // ID referensi (e.g. TP-205, IQ-2210, ID survei)
                $table->timestamp('read_at')->nullable()->index(); // Null = belum dibaca
                $table->timestamps();

                $table->index(['read_at', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};

