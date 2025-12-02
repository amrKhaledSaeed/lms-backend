<?php

namespace App\Repositories\GlobalRepository;

use App\Models\Box;
use App\Enums\BoxStatusEnum;
use App\Repositories\GlobalRepository\BaseRepository;

class GlobalRepository extends BaseRepository
{
    public function __construct($model)
    {
        $this->setModel($model);
    }

}
