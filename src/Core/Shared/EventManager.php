<?php
declare(strict_types=1);

namespace Core\Shared;

interface EventManager
{
    public function dispatch( object $event): void;
}
