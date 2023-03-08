<?php
declare(strict_types=1);

namespace App\Repositories\Presenters;

use Core\Shared\Domain\Collection;
use Core\Shared\Domain\ListCollection;
use Core\Shared\Domain\PaginationInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaginationPresenter implements PaginationInterface
{

    public function __construct(private readonly LengthAwarePaginator $paginator)
    {
    }

    public function items(): Collection
    {
        /**
         * Aqui esta sendo retornado uma Lista com objetos instancia de Model (infra). Posteriormente
         * podera ser necessario a conversao desse objetos para as entidades do domino.
         */
        return new ListCollection($this->paginator->items());
    }

    public function total(): int
    {
        return $this->paginator->total();
    }

    public function lastPage(): int
    {
        return $this->paginator->lastPage();
    }

    public function firstPage(): int
    {
        return $this->paginator->firstItem();
    }

    public function currentPage(): int
    {
        return $this->paginator->currentPage();
    }

    public function perPage(): int
    {
        return $this->paginator->perPage();
    }

    public function to(): int
    {
        return $this->paginator->firstItem();
    }

    public function from(): int
    {
        return $this->paginator->lastItem();
    }
}
