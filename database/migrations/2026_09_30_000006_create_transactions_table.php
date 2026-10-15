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
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('id')->primary(); // #CLS-2025-031
            $table->string('date');
            $table->string('property_id')->nullable()->index();
            $table->string('property_title');
            $table->text('property_image')->nullable();
            $table->string('location');
            $table->string('trx_type'); // TERJUAL - JUAL BELI, TERSEWA - 2 TAHUN, TERSEWA - 1 TAHUN
            $table->string('asking_price');
            $table->string('deal_price');
            $table->unsignedBigInteger('deal_val')->default(0);
            $table->string('diff_pct')->nullable();
            $table->string('parties')->nullable();
            $table->string('commission_val')->nullable();
            $table->string('notary')->nullable();
            $table->string('status_doc')->default('AJB Selesai');
            $table->string('days_on_market')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
