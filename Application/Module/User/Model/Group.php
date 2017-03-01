<?php

namespace APP\Application\Module\User\Model;

use APP\Application\Module\User\Model\DbTable\Group as DbGroup;
use APP\Engine\Module\Model;

class Group extends Model
{

    const ADMINISTRATOR = 1;
    const GUEST = -1;

    private $_aPerms = null;

    public function __construct()
    {
        $this->_oTable = new DbGroup();
        parent::__construct();
    }

    public function getPerms($iUserGroupId)
    {
        if (!$this->_aPerms)
        {
            $this->_aPerms = (new Permission())->getAllByGroup($iUserGroupId);
        }
        return $this->_aPerms;
    }

    public function getForEdit($mValue, $mTableKey = null, $sSelectFields = "*")
    {
        $mResult = parent::getOne($mValue, $mTableKey, $sSelectFields);
        $mResult->perms = array();
        if ($mResult->user_group_id)
        {
            $mResult->perms = $this->getPerms($mResult->user_group_id);
        }
        return $mResult;
    }

}
