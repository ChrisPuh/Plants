<?php

namespace App\Contracts\EventSourcing;

interface EventStore
{
    public function append(Event $event): void;

    public function getEventsForAggregate(string $aggregateId, ?int $fromVersion = null): iterable;

    public function getAllEvents(?int $fromVersion = null): iterable;

    public function getEventsByType(string $eventType): iterable;
}
