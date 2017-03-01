<?php

namespace APP\Application\Module\User\Model;

use APP\Application\Module\User\Model\DbTable\Permission as DbPermission;
use APP\Engine\Application;
use APP\Engine\Module\Model;
use APP\Engine\Database\Query;
use APP\Engine\AppException;
use APP\Engine\Logger;

class Permission extends Model
{

    const ACTIVATE = 1;
    const DEACTIVATE = 0;
    const IS_DEFAULT = 1;
    const NORMAL = 0;

    public function __construct()
    {
        $this->_oTable = new DbPermission();
        parent::__construct();
    }

    public function getAllByGroup($iGroupId = null, $bReturnObject = false)
    {
        $oQuery = new Query("select");
        $sGroupPermTableName = \APP\Engine\Database\DbTable::getFullTableName('user_group_permission');
        $sAlias = $this->getTable()->getAlias();
        $oQuery->from($sGroupPermTableName, $sGroupPermTableName)
                ->select($sAlias . '.*,' . $sGroupPermTableName . '.*')
                ->join($this->getTable()->getTableName(), $sGroupPermTableName . '.permission_id = ' . $sAlias . '.permission_id', $sAlias)
                ->where($sGroupPermTableName . '.user_group_id', $iGroupId);
        $mResult = $this->_oTable->executeQuery($oQuery);
        if ($bReturnObject)
        {
            return $mResult;
        }
        $aResult = array();
        if (is_array($mResult))
        {
            foreach ($mResult as $iKey => $mTmp)
            {
                if (!isset($mTmp['gp_value']))
                {
                    $mTmp['gp_value'] = $mTmp['is_default'];
                }
                $sKey = strtolower($mTmp['module'] . '.' . $mTmp['var_name']);
                $aResult[$sKey] = $mTmp;
            }
        }
        return $aResult;
    }

    /**
     * 
     * @param string $sPerm
     * @return boolean
     * Example: core.this_is_example_code
     * By default admin group permission will be true, others will be false
     */
    public function simpleAddPerm($sPerm = "")
    {
        $aParts = explode('.', $sPerm);
        $bResult = false;
        if (count($aParts) == 2)
        {
            $oPerm = $this->_oTable->createRow(array(
                'module' => ucfirst(strtolower($aParts[0])),
                'var_name' => strtolower($aParts[1]),
                'permission_title' => strtolower($sPerm) . "_title",
                'description' => strtolower($sPerm) . "_description",
                'is_active' => self::ACTIVATE,
                'is_default' => self::NORMAL,
            ));
            try
            {
                if ($oPerm->isValid())
                {
                    $bResult = $oPerm->save();
                }
            } catch (AppException $ex)
            {
                Logger::error($ex);
            }
        }
        return $bResult;
    }

}
