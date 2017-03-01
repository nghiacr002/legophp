<?php

namespace APP\Application\Module\Theme\Model\DbTable\DbRow;

use APP\Engine\Database\DbRow;
use APP\Engine\Database\Query;

class Menu extends DbRow
{

    public function delete()
    {
        if ($this->menu_id > 0)
        {
            $this->deleteSubItem($this->menu_id);
        }
        return parent::delete();
    }

    public function deleteSubItem($iMenuId)
    {
        $query = new Query();
        $query->setCommand("delete");
        $query->where('parent_id', $iMenuId);
        $query->from($this->_oTable->getTableName());
        $bResult = $this->_oTable->executeQuery($query);
        return $bResult;
    }

}
