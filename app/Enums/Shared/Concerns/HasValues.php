<?php

declare(strict_types=1);

namespace App\Enums\Shared\Concerns;

/**
 * Trait to provide a list of enum values
 */
trait HasValues
{
    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_values(array_map(
            static fn (self $case): string => $case->value,
            self::cases()
        ));
    }
}
