<?php

namespace APP\Application\Module\User\Model;

use APP\Application\Module\User\Model\DbTable\User as DbUser;
use APP\Engine\Application;
use APP\Engine\Module\Model;
use APP\Application\Module\User\Model\Auth as UserAuth;
use APP\Application\Module\User\Model\DbTable\DbRow\User as UserRow;
use APP\Engine\Database\Query;

class User extends Model
{

    const STATUS_ACTIVE = 1;
    const STATUS_PENDING = 0;

    public function __construct()
    {
        $this->_oTable = new DbUser();
        parent::__construct();
    }

    public function getOne($mValue, $mTableKey = null, $bCache = false)
    {
        if ($bCache)
        {
            $sCacheName = $this->_oTable->getTableName();
            if (!$mTableKey)
            {
                $mTableKey = $this->_oTable->getPrimaryKey();
            }
            $sCacheName = $sCacheName . md5(serialize($mValue) . serialize($mTableKey));
            if ($user = $this->cache()->get($sCacheName,"Model"))
            {
                return $user;
            }
        }
        $user = parent::getOne($mValue, $mTableKey);
        if ($user && $bCache)
        {
            $this->cache()->set($sCacheName, $user, $this->getTTL(), "Model");
        }
        return $user;
    }

    public function getAll($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "*", $mOrder = null)
    {
        $oQuery = new Query("select");
        $oQuery->from($this->_oTable->getTableName(), 'user');
        $oQuery->select($sSelectFields);
        $oQuery->limit($iPage, $iLimit);
        $oQuery->join(\APP\Engine\Database\DbTable::getFullTableName('user_group'), 'user_group.user_group_id = user.main_group_id', 'user_group');
        if (count($mOrder) == 2)
        {
            $oQuery->order($mOrder[0], $mOrder[1]);
        }
        if (count($aConds))
        {
            foreach ($aConds as $iKey => $aCond)
            {
                $param = isset($aCond[0]) ? $aCond[0] : "";
                $bind = isset($aCond[1]) ? $aCond[1] : "";
                $operator = isset($aCond[2]) ? $aCond[2] : "=";
                $cond_type = isset($aCond[3]) ? $aCond[3] : "AND";
                $oQuery->where($param, $bind, $operator, $cond_type);
            }
        }
        $mResults = $this->_oTable->executeQuery($oQuery);

        if (!isset($mResults[0]))
        {
            return null;
        }
        $mRows = array();
        foreach ($mResults as $iKey => $mResult)
        {
            $oRow = new UserRow($this->_oTable);
            $oRow->setData($mResult);
            $mRows[] = $oRow;
        }
        return $mRows;
    }

    public function remember($iUserId)
    {
        $oUser = $this->getOne($iUserId, 'user_id');
        if ($oUser->user_id)
        {
            list($sHash, $sPassword) = (new UserAuth())->getHash($oUser->password, $oUser->hash);
            $this->app->cookie->set('user_id', $oUser->user_id);
            $this->app->cookie->set('user_hash', $sPassword);
        }
    }

}
