<?php

namespace APP\Application\Module\Core\Model;

use APP\Application\Module\Core\Model\DbTable\Module as DbModule;
use APP\Engine\Application;
use APP\Engine\Module\Model;

class Module extends Model
{

    public function __construct()
    {
        $this->_oTable = new DbModule();
        parent::__construct();
    }

    public function getAll($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "*", $mOrder = null)
    {
        $sCacheName = $this->_oTable->getTableName();
        $sCacheName = $sCacheName . md5($sSelectFields . $mOrder . $iPage . $iLimit);
        if ($oModules = $this->cache()->get($sCacheName,"Model"))
        {
            return $oModules;
        }
        $oModules = parent::getAll($aConds, $iPage, $iLimit, $sSelectFields, $mOrder);
        if ($oModules)
        {
            $this->cache()->set($sCacheName, $oModules, $this->getTTL(), "Model");
        }
        return $oModules;
    }

}
