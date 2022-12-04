<?php

declare(strict_types=1);

namespace Core\Shared\Domain;

use ArrayIterator;

/**
 * @template T
 * @implements Collection<T>
 */
class ListCollection implements Collection
{
    /**
     * @param array<T> $items
     */
    public function __construct(
        private array $items = []
    ) {
    }

    /**
     * @return ArrayIterator<array-key,T>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return array<T>
     */
    public function items(): array
    {
        return $this->items;
    }
}
