<?php

namespace APP\Application\Module\Core\Model;

use APP\Application\Module\Core\Model\DbTable\Category as DbCategory;
use APP\Engine\Application;
use APP\Engine\Module\Model;

class Category extends Model
{

    protected $_sType;

    const ACTIVATED = 1;

    public function __construct($sType = "")
    {
        $this->_oTable = new DbCategory();
        parent::__construct();
        $this->_sType = $sType;
    }

    public function setType($sType = "system")
    {
        $this->_sType = $sType;
        return $this;
    }

    public function getCategoriesByType($sType = "system", $iParentId = 0, $bIsEditMode = false)
    {
        $aConds = array(
            'category_type' => array(
                'category_type', $sType
            ),
            'and-2' => array(
                'is_active', self::ACTIVATED
            )
        );
        if ($bIsEditMode)
        {
            unset($aConds['and-2']);
        }
        $aConds['and-3'] = array(
            'parent_id', (int) $iParentId
        );

        $aCategories = array();
        $aRows = $this->getAll($aConds, null, null, '*', array('ordering', 'ASC'));
        if (count($aRows))
        {
            foreach ($aRows as $iKey => $oRow)
            {
                $aRow = $oRow->getProps();
                if ($aRow['parent_id'] == 0)
                {
                    $aRow['sub'] = $this->getCategoriesByType($sType, $aRow['category_id'], $bIsEditMode);
                }
                $aCategories[$aRow['category_id']] = $aRow;
            }
        }
        return $aCategories;
    }
    public function getAllWithCache($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "*", $mOrder = null)
    {
    	if (!empty($this->_sType))
    	{
    		$aConds[] = array(
    				'category_type', $this->_sType
    		);
    	}
	    $sCacheName = $this->_oTable->getTableName();
        $sCacheName = $sCacheName . md5($sSelectFields . serialize($mOrder) . $iPage . $iLimit);
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

    public function getAll($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "*", $mOrder = null)
    {
        if (!empty($this->_sType))
        {
            $aConds['category_type'] = array(
                'category_type', $this->_sType
            );
        }
        return parent::getAll($aConds, $iPage, $iLimit, $sSelectFields, $mOrder);
    }

}
