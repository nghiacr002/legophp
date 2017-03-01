<?php

namespace APP\Application\Module\Core\Model;

use APP\Application\Module\Core\Model\DbTable\Setting as DbSetting;
use APP\Engine\Module\Model;
use APP\Engine\Database\Query;

class Setting extends Model
{

    public function __construct()
    {
        $this->_oTable = new DbSetting();
        parent::__construct();
    }

    public function getAll($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "*", $mOrder = null)
    {
        $sCacheName = $this->_oTable->getTableName();
        $sCacheName = $sCacheName . md5($sSelectFields . $mOrder . $iPage . $iLimit);

        if ($aSettings = $this->cache()->get($sCacheName))
        {
            return $aSettings;
        }
        $aSettings = parent::getAll($aConds, $iPage, $iLimit, $sSelectFields, $mOrder);
        $aResult = array();
        if (count($aSettings))
        {
            foreach ($aSettings as $iKey => $aSetting)
            {
                $aRow = $aSetting->toArray();
                $aRow['value'] = ($aRow['real_value'] === null ) ? $aRow['default_value'] : $aRow['real_value'];
                $aResult[$aRow['module'] . '.' . $aRow['var_name']] = $aRow;
            }
        }
        if ($aResult)
        {
            $this->cache()->set($sCacheName, $aResult, 100, "Model");
        }
        return $aResult;
    }

    public function getGroupSettings()
    {
        $oQuery = new Query("select");
        $oQuery->select('module')
                ->from($this->getTable()->getTableName())
                ->group('module');
        $mResult = $this->getTable()->executeQuery($oQuery);
        $aResult = array();
        if (count($mResult))
        {
            foreach ($mResult as $iKey => $aName)
            {
                $aResult[] = $aName['module'];
            }
        }
        return $aResult;
    }

}
