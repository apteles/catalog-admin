<?php
declare(strict_types=1);

namespace App\Repositories\Transaction;

use Core\Shared\Repository\Transaction;
use Illuminate\Support\Facades\DB;

class DBTransaction implements Transaction
{

    public function __construct()
    {
        DB::beginTransaction();
    }

    public function commit()
    {
        DB::commit();
    }

    public function rollback()
    {
        DB::rollBack();
    }
}
