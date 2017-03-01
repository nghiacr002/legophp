<?php

namespace APP\Application\Module\Core\Model;

use APP\Application\Module\Core\Model\DbTable\Note as DbNote;
use APP\Engine\Application;
use APP\Engine\Module\Model;

class Note extends Model
{

    public function __construct()
    {
        $this->_oTable = new DbNote();
        parent::__construct();
    }

}
