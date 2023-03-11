<?php
declare(strict_types=1);

namespace Core\Domain\Events;
/**
 * @template T
 */
interface Event
{
    public function getEventName(): string;

    /**
     * @return T
     */
    public function getPayload(): mixed;
}
