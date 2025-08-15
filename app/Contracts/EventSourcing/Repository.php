<?php

namespace App\Contracts\EventSourcing;

interface Repository
{
    public function load(string $aggregateId): Aggregate;

    public function save(Aggregate $aggregate): void;
}
