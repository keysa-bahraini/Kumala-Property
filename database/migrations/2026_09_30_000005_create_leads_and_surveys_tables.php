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
        Schema::create('leads', function (Blueprint $table) {
            $table->string('id')->primary(); // #IQ-2209
            $table->string('property_id')->nullable();
            $table->foreign('property_id')->references('id')->on('properties')->nullOnDelete();
            $table->string('property_title')->nullable();
            $table->string('property_price')->nullable();
            $table->string('client_name');
            $table->string('client_phone');
            $table->string('client_email')->nullable();
            $table->string('buyer_category')->default('Pembeli Rumah Tinggal');
            $table->text('message')->nullable();
            $table->text('quote')->nullable();
            $table->string('inquiry_type')->default('beli');
            $table->string('payment_scheme')->nullable();
            $table->string('survey_date_preference')->nullable();
            $table->string('assigned_agent')->nullable();
            $table->enum('stage', ['Baru', 'Follow-Up', 'Survei', 'Closing', 'Selesai'])->default('Baru');
            $table->integer('sla_minutes')->default(15);
            $table->string('sla_badge')->nullable();
            $table->string('sla_status')->default('normal'); // urgent, normal, safe
            $table->string('time_ago')->nullable();
            $table->timestamps();
        });

        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('lead_id')->nullable();
            $table->foreign('lead_id')->references('id')->on('leads')->nullOnDelete();
            $table->string('property_id')->nullable();
            $table->foreign('property_id')->references('id')->on('properties')->nullOnDelete();
            $table->string('property_title');
            $table->string('client_name');
            $table->string('agent_name');
            $table->string('survey_time'); // e.g. 10:30 WIB
            $table->date('survey_date')->nullable();
            $table->string('status')->default('Menunggu Klien'); // Terkonfirmasi, Menunggu Klien, Selesai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
        Schema::dropIfExists('leads');
    }
};
