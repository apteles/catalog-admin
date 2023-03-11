<?php

declare(strict_types=1);

namespace Core\Domain\Repositories;

use Core\Domain\Entities\CastMember;

/**
 * @extends Repository<CastMember>
 */
interface CastMemberRepository extends Repository
{
    public function getIdsListIds(array $membersIds = []): array;
}
