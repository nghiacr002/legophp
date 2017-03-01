<?php

namespace APP\Engine\Module;

use APP\Engine\Module\Component;
use APP\Engine\Database\Query;

class Model extends Component
{

    protected $_oTable;
    protected $_oCache;
    protected $_iTimeToLive = 100; //seconds

    public function __construct()
    {
        if (!$this->_oTable)
        {
            $this->_oTable = new \APP\Engine\Database\DbTable();
        }
        parent::__construct();
    }

    public function getTTL()
    {
        return $this->_iTimeToLive;
    }

    public function cache($iTTL = 100)
    {
        if (!$this->_oCache)
        {
            $this->_oCache = (new \APP\Engine\Cache())->getStorage();
        }
        $this->_iTimeToLive = $iTTL;
        return $this->_oCache;
    }

    public function getTable()
    {
        return $this->_oTable;
    }

    public function getOne($mValue, $mTableKey = null, $sSelectFields = "*")
    {
        if (!$mTableKey)
        {
            $mTableKey = $this->_oTable->getPrimaryKey();
        }
        $oQuery = new Query("select");

        $oQuery->select($sSelectFields);
        $oQuery->from($this->_oTable->getTableName());
        if (is_array($mTableKey))
        {

            foreach ($mTableKey as $index => $mKey)
            {
                $oQuery->where($mKey, isset($mValue[$index]) ? $mValue[$index] : null);
            }
        } else
        {
            $oQuery->where($mTableKey, $mValue);
        }
        $mResult = $this->_oTable->executeQuery($oQuery);

        if (!isset($mResult[0]))
        {
            return null;
        }
        $oRow = new \APP\Engine\Database\DbRow($this->_oTable);
        $oRow->setData($mResult[0]);
        if (!empty($this->_oTable->getRowClass()))
        {
            $oRow = system_cast_object($oRow, $this->_oTable->getRowClass());
        }
        return $oRow;
    }

    public function getAll($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "*", $mOrder = null)
    {
        $oQuery = new Query("select");
        $oQuery->from($this->_oTable->getTableName());
        $oQuery->select($sSelectFields);
        $oQuery->limit($iPage, $iLimit);
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
            $oRow = new \APP\Engine\Database\DbRow($this->_oTable);
            $oRow->setData($mResult);

            if (!empty($this->_oTable->getRowClass()))
            {
                $oRow = system_cast_object($oRow, $this->_oTable->getRowClass());
            }
            $mRows[] = $oRow;
        }
        return $mRows;
    }

    public function getTotal($aConds = array())
    {
        $oQuery = new Query("select");
        $oQuery->from($this->_oTable->getTableName(), $this->getTable()->getAlias());
        $oQuery->select('COUNT(*) as TOTAL');
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
        $mResult = $this->_oTable->executeQuery($oQuery);
        if (!isset($mResult[0]))
        {
            return null;
        }
        return (int) $mResult[0]['TOTAL'];
    }

}
