<?php

namespace APP\Application\Module\User\Model\DbTable\DbRow;

use APP\Engine\Database\DbRow;

class Permission extends DbRow
{

    public function toArray($bBasicInfo = false)
    {
        return $this->_aData;
    }

}
