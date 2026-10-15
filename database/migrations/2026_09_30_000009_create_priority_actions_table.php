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
        Schema::create('priority_actions', function (Blueprint $table) {
            $table->id();
            $table->string('action_id'); // #TP-204, #IQ-2209
            $table->string('type')->default('listing'); // listing, inquiry, system
            $table->string('title');
            $table->string('owner')->nullable();
            $table->string('phone')->nullable();
            $table->string('time_ago')->nullable();
            $table->string('sla_status')->default('normal'); // urgent, warning, normal
            $table->string('sla_badge')->nullable();
            $table->text('detail')->nullable();
            $table->string('btn_action_1')->nullable();
            $table->string('btn_action_2')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('priority_actions');
    }
};
