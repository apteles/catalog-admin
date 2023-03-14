<?php
declare(strict_types=1);

namespace Core\Shared;

interface Storage
{
    public function store(string $path, array $file): string;

    public function delete(string $path): void;
}
