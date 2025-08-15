<?php

namespace App\Events\Request;

use App\Contracts\EventSourcing\Event;

class PlantRequestApproved implements Event
{
    public function __construct(
        private string $aggregateId,
        private string $adminUserId,
        private ?string $notes,
        private string $createdPlantId,
        private \DateTimeInterface $occurredAt,
        private int $version
    ) {}

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getAggregateType(): string
    {
        return 'plant_request';
    }

    public function getEventType(): string
    {
        return 'plant_request.approved';
    }

    public function getPayload(): array
    {
        return [
            'admin_user_id' => $this->adminUserId,
            'notes' => $this->notes,
            'created_plant_id' => $this->createdPlantId,
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
