<?php

namespace App\Enums\Plant;

use App\Enums\Shared\Concerns\HasValues;

enum PlantTypeEnum: string
{
    use HasValues;

    case Annual = "annual";
    case Biennial = "biennial";
    case Perennial = "perennial";
    case Shrub = "shrub";
    case Tree = "tree";
    case Vine = "vine";
    case Fern = "fern";
    case Moss = "moss";
    case Succulent = "succulent";


    public function label(): string
    {
        return match ($this) {
            self::Annual => "annual",
            self::Biennial => "biennial",
            self::Perennial => "perennial",
            self::Shrub => "shrub",
            self::Tree => "tree",
            self::Vine => "vine",
            self::Fern => "fern",
            self::Moss => "moss",
            self::Succulent => "succulent",

        };

    }
}
