<?php

namespace APP\Application\Module\Theme\Model;

use APP\Application\Module\Theme\Model\DbTable\Theme as DbTheme;
use APP\Engine\Application;
use APP\Engine\Module\Model;
use APP\Engine\Database\Query;

class Theme extends Model
{

    public function __construct()
    {
        $this->_oTable = new DbTheme();
        parent::__construct();
    }

    public function getAll($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "*", $mOrder = null)
    {
        $sCacheName = $this->_oTable->getTableName();
        $sCacheName = $sCacheName . md5($sSelectFields . $mOrder . $iPage . $iLimit);
        if ($aRows = $this->cache()->get($sCacheName,"Model"))
        {
            return $aRows;
        }
        $aRows = parent::getAll($aConds, $iPage, $iLimit, $sSelectFields, $mOrder);
        if ($aRows)
        {
            $this->cache()->set($sCacheName, $aRows, $this->getTTL(), "Model");
        }
        return $aRows;
    }

    public function getDefaultTheme($sMode = "frontend")
    {
        return $this->getOne(array(1,$sMode), array('is_default','type'));
    }

    public function setDefault($iId)
    {
        //set off all
        $oQuery = new Query("update");
        $oQuery->setTableData($this->getTable()->getTableName(), array(
            'is_default' => 0,
        ));
        $bResult1 = $this->getTable()->executeQuery($oQuery);
        $oQuery->clean();
        $oQuery->where('theme_id', $iId);
        $oQuery->setTableData($this->getTable()->getTableName(), array(
            'is_active' => 1,
            'is_default' => 1,
        ));
        $bResult2 = $this->getTable()->executeQuery($oQuery);
        return $bResult2;
    }

}
