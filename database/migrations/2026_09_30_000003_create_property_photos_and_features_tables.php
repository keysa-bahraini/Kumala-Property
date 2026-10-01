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
        Schema::create('property_photos', function (Blueprint $table) {
            $table->id();
            $table->string('property_id');
            $table->foreign('property_id')->references('id')->on('properties')->cascadeOnDelete();
            $table->text('photo_url');
            $table->string('caption')->nullable();
            $table->boolean('is_main')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('property_features', function (Blueprint $table) {
            $table->id();
            $table->string('property_id');
            $table->foreign('property_id')->references('id')->on('properties')->cascadeOnDelete();
            $table->string('title');
            $table->text('desc')->nullable();
            $table->string('icon')->default('star');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_features');
        Schema::dropIfExists('property_photos');
    }
};
