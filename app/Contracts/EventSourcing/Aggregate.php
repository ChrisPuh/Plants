<?php

namespace App\Contracts\EventSourcing;

interface Aggregate
{
    public function getId(): string;

    public function getVersion(): int;

    public function getUncommittedEvents(): array;

    public function markEventsAsCommitted(): void;

    public function loadFromHistory(iterable $events): void;
}
