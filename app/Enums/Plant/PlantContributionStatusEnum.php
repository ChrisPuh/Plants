<?php

namespace App\Enums\Plant;

use App\Enums\Shared\Concerns\HasValues;

enum PlantContributionStatusEnum: string
{
    use HasValues;

    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::Approved, self::Rejected]);
    }
}
