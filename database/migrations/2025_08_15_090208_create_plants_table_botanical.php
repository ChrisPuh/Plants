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
        // Drop existing plants table if it exists
        Schema::dropIfExists('plants');

        // Create new plants table with botanical structure
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('latin_name')->nullable();
            $table->string('family')->nullable(); // Botanical family
            $table->string('genus')->nullable(); // Genus
            $table->string('species')->nullable(); // Species
            $table->string('common_names')->nullable(); // Alternative common names
            $table->text('description')->nullable();
            $table->text('botanical_description')->nullable(); // Detailed botanical description
            $table->string('category')->nullable(); // e.g. herb, flower, vegetable, tree
            $table->enum('plant_type', ['annual', 'biennial', 'perennial', 'shrub', 'tree', 'vine', 'fern', 'moss', 'succulent'])->nullable();
            $table->enum('growth_habit', ['upright', 'spreading', 'climbing', 'trailing', 'rosette', 'clumping'])->nullable();
            $table->string('native_region')->nullable(); // Geographic origin
            $table->integer('height_min_cm')->nullable();
            $table->integer('height_max_cm')->nullable();
            $table->integer('width_min_cm')->nullable();
            $table->integer('width_max_cm')->nullable();
            $table->string('bloom_time')->nullable(); // e.g. spring, summer, fall
            $table->string('flower_color')->nullable();
            $table->json('leaf_characteristics')->nullable(); // Leaf shape, size, arrangement etc.
            $table->json('flower_characteristics')->nullable(); // Flower details
            $table->json('fruit_characteristics')->nullable(); // Fruit/seed details
            $table->string('bark_type')->nullable(); // For trees/shrubs
            $table->enum('foliage_type', ['deciduous', 'evergreen', 'semi-evergreen'])->nullable();
            $table->boolean('is_edible')->default(false);
            $table->boolean('is_toxic')->default(false);
            $table->json('propagation_methods')->nullable(); // JSON array e.g. ["seed", "cutting", "division"]
            $table->string('image_url')->nullable();
            $table->json('images')->nullable(); // Multiple images
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
