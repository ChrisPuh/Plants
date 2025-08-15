<?php

namespace App\Models;

use App\Enums\Plant\PlantCategoryEnum;
use App\Enums\Plant\PlantTypeEnum;
use Database\Factories\PlantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    /** @use HasFactory<PlantFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'latin_name',
        'description',
        'category',
        'plant_type',
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'string',
            'latin_name' => 'string',
            'description' => 'string',
            'category' => PlantCategoryEnum::class,
            'plant_type' => PlantTypeEnum::class,
            'image_url' => 'string',

        ];
    }
}
