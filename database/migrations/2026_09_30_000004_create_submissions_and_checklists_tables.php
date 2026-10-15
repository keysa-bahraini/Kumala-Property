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
        Schema::create('submissions', function (Blueprint $table) {
            $table->string('id')->primary(); // #TP-204
            $table->string('property_id')->nullable();
            $table->string('property_slug')->nullable();
            $table->string('title');
            $table->string('owner_name');
            $table->string('owner_phone');
            $table->string('owner_email');
            $table->string('owner_status')->default('Pemilik Langsung');
            $table->string('trx_type')->default('Jual');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->string('category')->nullable();
            $table->string('city_region')->nullable();
            $table->string('specific_area')->nullable();
            $table->string('location')->nullable();
            $table->text('address')->nullable();
            $table->string('asking_price')->nullable();
            $table->unsignedBigInteger('asking_val')->default(0);
            $table->string('valuation_range')->nullable();
            $table->integer('land_size')->default(0);
            $table->integer('building_size')->default(0);
            $table->string('bedrooms')->nullable();
            $table->string('bathrooms')->nullable();
            $table->string('electricity')->nullable();
            $table->string('legal')->nullable();
            $table->json('facilities')->nullable();
            $table->json('photos')->nullable();
            $table->integer('photos_count')->default(0);
            $table->string('priority')->default('Normal');
            $table->enum('status', ['pending', 'revision', 'approved', 'rejected'])->default('pending');
            $table->json('checklist')->nullable();
            $table->text('notes')->nullable();
            $table->string('time_ago')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('submission_checklists', function (Blueprint $table) {
            $table->id();
            $table->string('submission_id');
            $table->foreign('submission_id')->references('id')->on('submissions')->cascadeOnDelete();
            $table->boolean('identity')->default(false);
            $table->boolean('maps')->default(false);
            $table->boolean('shm_match')->default(false);
            $table->boolean('photos_hd')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_checklists');
        Schema::dropIfExists('submissions');
    }
};
