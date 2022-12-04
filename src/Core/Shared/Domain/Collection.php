<?php
declare(strict_types=1);

namespace Core\Shared\Domain;

use Countable;
use IteratorAggregate;

/**
 * @template T
 * @extends IteratorAggregate<int, T>
 */
interface Collection extends Countable, IteratorAggregate
{
    /**
     * @return array<T>
     */
    public function items(): array;
}