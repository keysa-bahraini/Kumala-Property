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
        Schema::create('properties', function (Blueprint $table) {
            $table->string('id')->primary(); // #KP-8821
            $table->string('slug')->unique();
            $table->string('title');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->string('category')->nullable();
            $table->string('transaction_type')->default('Jual'); // Jual, Sewa, Bisa Jual/Sewa
            $table->unsignedBigInteger('price');
            $table->string('price_formatted')->nullable();
            $table->string('price_short')->nullable();
            $table->string('price_label')->nullable();
            $table->string('price_notes')->nullable();
            $table->boolean('price_negotiable')->default(true);
            $table->string('location_city');
            $table->string('location_area');
            $table->text('location_address');
            $table->string('legal_status')->default('SHM (Hak Milik)');
            $table->boolean('verified')->default(true);
            $table->boolean('featured')->default(false);
            $table->string('highlight_badge')->nullable();
            $table->integer('land_size')->default(0);
            $table->integer('building_size')->default(0);
            $table->integer('floors')->default(1);
            $table->integer('bedrooms')->default(0);
            $table->integer('bathrooms')->default(0);
            $table->string('orientation')->nullable();
            $table->string('electricity')->nullable();
            $table->string('water_source')->nullable();
            $table->string('garage')->nullable();
            $table->string('year_built')->nullable();
            $table->string('furnishing')->nullable();
            $table->json('specs_tags')->nullable();
            $table->text('image_main');
            $table->json('images')->nullable();
            $table->json('features')->nullable();
            $table->string('video_tour_url')->nullable();
            $table->string('pdf_factsheet_url')->nullable();
            $table->text('description');
            $table->json('distance_points')->nullable();
            $table->enum('status', ['DRAFT', 'REVIEW', 'ACTIVE', 'ARCHIVED', 'SOLD', 'RENTED'])->default('ACTIVE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
