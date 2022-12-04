<?php

declare(strict_types=1);

namespace Core\Shared\Domain;

/**
 * @template T
 */
interface PaginationInterface
{
    /**
     * @return Collection<T>
     */
    public function items(): Collection;

    public function total(): int;

    public function lastPage(): int;

    public function firstPage(): int;

    public function currentPage(): int;

    public function perPage(): int;

    public function to(): int;

    public function from(): int;
}
