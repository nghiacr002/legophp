<?php

namespace APP\Application\Module\Core\Model;

use APP\Application\Module\Core\Model\DbTable\Stats as DbStats;

class Stats extends Model
{

    public function __construct()
    {
        $this->_oTable = new DbStats();
        parent::__construct();
    }

}
