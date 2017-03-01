<?php

namespace APP\Application\Module\Core\Model;

use APP\Application\Module\Core\Model\DbTable\HashTag as DbHashTag;
use APP\Engine\Application;
use APP\Engine\Module\Model;
use APP\Application\Module\Core\Model\DbTable\DbRow\HashTag as HashTagRow;
use APP\Engine\Database\Query;

class HashTag extends Model
{

    public function __construct()
    {
        $this->_oTable = new DbHashTag();
        parent::__construct();
    }

    public function analyze($sPhrase)
    {
        $aHash = explode(',', $sPhrase);
        return $aHash;
    }

    public function updateHash($aHash = array(), $iItemId, $sType)
    {
        $this->deleteByItem($iItemId, $sType);
        return $this->insertHash($aHash, $iItemId, $sType);
    }

    public function deleteByItem($iItemId, $sType)
    {
        $oQuery = new Query("delete");
        $oQuery->where('item_id', $iItemId);
        $oQuery->where('item_type', $sType);
        $oQuery->from($this->getTable()->getTableName());
        return $this->getTable()->executeQuery($oQuery);
    }

    public function insertHash($aHash = array(), $iItemId, $sType)
    {
        $aHash = trim($aHash);
        if (empty($aHash))
        {
            return false;
        }
        if (!is_array($aHash))
        {
            $aHash = $this->analyze($aHash);
        }
        $aHashCodeReturn = array();
        foreach ($aHash as $iKey => $sHash)
        {
            $oRowHash = new HashTagRow($this->getTable());
            $sHash = trim($sHash);
            $aInsert = array(
                'hashtag_name' => $sHash,
                'hashtag_code' => $this->getHashCode($sHash),
                'item_id' => $iItemId,
                'item_type' => $sType,
            );
            try
            {
                if ($iId = $oRowHash->setData($aInsert)->save())
                {
                    $aHashCodeReturn[$iId] = $aInsert['hashtag_code'];
                }
            } catch (\AppException $ex)
            {
                //do nothing
            }
        }
        return $oRowHash;
    }

    public function getHashCode($sString)
    {
        return $sString;
    }

    public function getByItem($iItemId, $sType, $bConvert = true)
    {
        $aConds = array(
            array(
                'item_id', $iItemId,
            ),
            array(
                'item_type', $sType,
            )
        );
        $aRows = $this->getAll($aConds);

        if (!$bConvert)
        {
            return $aRows;
        }
        $sReturn = "";
        if (count($aRows))
        {
            foreach ($aRows as $iKey => $aRow)
            {
                $sReturn[] = $aRow->hashtag_name;
            }
            $sReturn = implode(",", $sReturn);
        }
        return $sReturn;
    }

    public function getByType($sType)
    {
        $aConds = array(
            array(
                'item_type', $sType
            )
        );
        return $this->getAll($aConds);
    }

    public function getAll($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "*", $mOrder = null)
    {
        $sCacheName = $this->_oTable->getTableName();
        $sCacheName = $sCacheName . md5($sSelectFields . $mOrder . $iPage . $iLimit);
        if ($aRows = $this->cache()->get($sCacheName))
        {
            return $aRows;
        }
        $aRows = parent::getAll($aConds, $iPage, $iLimit, $sSelectFields, $mOrder);

        if ($aRows)
        {
            $this->cache()->set($sCacheName, $aRows, 100, "Model");
        }
        return $aRows;
    }

}
