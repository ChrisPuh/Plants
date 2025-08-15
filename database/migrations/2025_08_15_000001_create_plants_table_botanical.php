<?php

use App\Enums\Plant\PlantCategoryEnum;
use App\Enums\Plant\PlantTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create new plants table with botanical structure
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('latin_name')->nullable();
            $table->text('description')->nullable();

            $table->enum('category', PlantCategoryEnum::values())->nullable(); // e.g. herb, flower, vegetable, tree
            $table->enum('plant_type', PlantTypeEnum::values())->nullable();

            $table->string('image_url')->nullable();

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
