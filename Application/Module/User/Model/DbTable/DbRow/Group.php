<?php

namespace APP\Application\Module\User\Model\DbTable\DbRow;

use APP\Engine\Database\DbRow;
use APP\Engine\Database\Query;

class Group extends DbRow
{

    public function toArray($bBasicInfo = false)
    {
        return $this->_aData;
    }

    public function emptyPerms()
    {
        $query = new Query();
        $query->setCommand("delete");
        $sTable = \APP\Engine\Database\DbTable::getFullTableName('user_group_permission');
        $query->from($sTable);
        $query->where('user_group_id', $this->user_group_id);
        $bResult = $this->_oTable->executeQuery($query);
        return $bResult;
    }

}
