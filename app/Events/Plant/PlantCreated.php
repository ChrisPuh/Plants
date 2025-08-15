<?php

namespace App\Events\Plant;

use App\Contracts\EventSourcing\Event;

class PlantCreated implements Event
{
    public function __construct(
        private string $aggregateId,
        private array $plantData,
        private \DateTimeInterface $occurredAt,
        private int $version = 1
    ) {}

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getAggregateType(): string
    {
        return 'plant';
    }

    public function getEventType(): string
    {
        return 'plant.created';
    }

    public function getPayload(): array
    {
        return [
            'plant_data' => $this->plantData,
        ];
    }

    public function getOccurredAt(): \DateTimeInterface
    {
        return $this->occurredAt;
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}
