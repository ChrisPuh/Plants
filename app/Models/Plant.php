<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    /** @use HasFactory<\Database\Factories\PlantFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'latin_name',
        'family',
        'genus',
        'species',
        'common_names',
        'description',
        'botanical_description',
        'category',
        'plant_type',
        'growth_habit',
        'native_region',
        'height_min_cm',
        'height_max_cm',
        'width_min_cm',
        'width_max_cm',
        'bloom_time',
        'flower_color',
        'leaf_characteristics',
        'flower_characteristics',
        'fruit_characteristics',
        'bark_type',
        'foliage_type',
        'is_edible',
        'is_toxic',
        'propagation_methods',
        'image_url',
        'images',
    ];

    protected function casts(): array
    {
        return [
            'is_edible' => 'boolean',
            'is_toxic' => 'boolean',
            'propagation_methods' => 'array',
            'leaf_characteristics' => 'array',
            'flower_characteristics' => 'array',
            'fruit_characteristics' => 'array',
            'images' => 'array',
            'height_min_cm' => 'integer',
            'height_max_cm' => 'integer',
            'width_min_cm' => 'integer',
            'width_max_cm' => 'integer',
        ];
    }

    public function getHeightRangeAttribute(): ?string
    {
        if ($this->height_min_cm && $this->height_max_cm) {
            return "{$this->height_min_cm}-{$this->height_max_cm} cm";
        }

        return $this->height_min_cm ? "{$this->height_min_cm} cm" : null;
    }

    public function getWidthRangeAttribute(): ?string
    {
        if ($this->width_min_cm && $this->width_max_cm) {
            return "{$this->width_min_cm}-{$this->width_max_cm} cm";
        }

        return $this->width_min_cm ? "{$this->width_min_cm} cm" : null;
    }

    public function getFullLatinNameAttribute(): string
    {
        $parts = array_filter([$this->genus, $this->species]);

        return implode(' ', $parts) ?: $this->latin_name;
    }

    public function getAllCommonNamesAttribute(): array
    {
        $names = [$this->name];
        if ($this->common_names) {
            $names = array_merge($names, explode(',', $this->common_names));
        }

        return array_map('trim', array_unique(array_filter($names)));
    }
}
