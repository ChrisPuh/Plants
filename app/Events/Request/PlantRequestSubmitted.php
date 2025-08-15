<?php

namespace App\Events\Request;

use App\Contracts\EventSourcing\Event;

class PlantRequestSubmitted implements Event
{
    public function __construct(
        private string $aggregateId,
        private string $userId,
        private array $requestData,
        private \DateTimeInterface $occurredAt,
        private int $version = 1
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
        return 'plant_request.submitted';
    }

    public function getPayload(): array
    {
        return [
            'user_id' => $this->userId,
            'request_data' => $this->requestData,
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
