<?php

namespace App\Events\Plant;

use App\Contracts\EventSourcing\Event;

class PlantUpdated implements Event
{
    public function __construct(
        private string $aggregateId,
        private array $changes,
        private \DateTimeInterface $occurredAt,
        private int $version
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
        return 'plant.updated';
    }

    public function getPayload(): array
    {
        return [
            'changes' => $this->changes,
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
