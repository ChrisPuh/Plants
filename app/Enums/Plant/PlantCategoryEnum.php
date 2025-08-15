<?php

namespace App\Enums\Plant;

use App\Enums\Shared\Concerns\HasValues;

enum PlantCategoryEnum: string
{
    use HasValues;

    case HERB = 'herb';
    case FLOWER = 'flower';
    case VEGETABLE = 'vegetable';
    case TREE = 'tree';
    case SHRUB = 'shrub';
    case CACTUS = 'cactus';
    case SUCCULENT = 'succulent';
    case FERN = 'fern';
    case AQUATIC = 'aquatic';
    case ORCHID = 'orchid';
    case GRASS = 'grass';
    case MOSS = 'moss';

    //labels for display
    public function label(): string
    {
        return match ($this) {
            self::HERB => 'Herb',
            self::FLOWER => 'Flower',
            self::VEGETABLE => 'Vegetable',
            self::TREE => 'Tree',
            self::SHRUB => 'Shrub',
            self::CACTUS => 'Cactus',
            self::SUCCULENT => 'Succulent',
            self::FERN => 'Fern',
            self::AQUATIC => 'Aquatic',
            self::ORCHID => 'Orchid',
            self::GRASS => 'Grass',
            self::MOSS => 'Moss',
        };
    }

}
