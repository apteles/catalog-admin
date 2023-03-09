<?php
declare(strict_types=1);

namespace Core\Shared\Repository;

interface Transaction
{
    public function commit();

    public function rollback();
}
