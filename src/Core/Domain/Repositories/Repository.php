<?php

declare(strict_types=1);

namespace Core\Domain\Repositories;

use Core\Shared\Domain\Collection;
use Core\Shared\Domain\PaginationInterface;

/**
 * @template T
 */
interface Repository
{
    /**
     * @param T $entity
     * @return T
     */
    public function create(mixed $entity);

    /**
     * @param string $filter
     * @param string $order
     * @return Collection<T>
     */
    public function all(string $filter = '', string $order = 'DESC'): Collection;

    /**
     * @param string $filter
     * @param string $order
     * @param int $currentPage
     * @param int $total
     * @return PaginationInterface<T>
     */
    public function paginate(
        string $filter = '',
        string $order = 'DESC',
        int $currentPage = 1,
        int $total = 15,
    ): PaginationInterface;

    /**
     * @param T $entity
     * @return T
     */
    public function update(mixed $entity);

    /**
     * @param string $id
     * @return T
     */
    public function findById(string $id);

    /**
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;

    /**
     * @param object $model
     * @return T
     */
    public function toEntity(object $model);
}
