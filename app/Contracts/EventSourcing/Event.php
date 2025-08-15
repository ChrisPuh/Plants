<?php

namespace App\Contracts\EventSourcing;

interface Event
{
    public function getAggregateId(): string;

    public function getAggregateType(): string;

    public function getEventType(): string;

    public function getPayload(): array;

    public function getOccurredAt(): \DateTimeInterface;

    public function getVersion(): int;
}
