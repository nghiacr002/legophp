<?php

namespace APP\Engine\Database;

use APP\Engine\Cache;

class DbTable extends \APP\Engine\Object
{

    protected $_sTableName;
    protected $_mPrimaryKey;
    protected $_oQuery;
    protected $_aValidateRules;
    protected $_sRowClass;
    protected $_sAlias;

    public function __construct($sTableName = "", $mPrimaryKey = null)
    {
        if (empty($sTableName))
        {
            $sTableName = $this->_sTableName;
        }
        $this->_sTableName = self::getFullTableName($sTableName);
        if ($mPrimaryKey)
        {
            $this->_mPrimaryKey = $mPrimaryKey;
        }
    }

    public function getRowClass()
    {
        return $this->_sRowClass;
    }

    public function businessValidate(\APP\Engine\Database\DbRow $mData)
    {
        return true;
    }

    public function getValidateRules()
    {
        return $this->_aValidateRules;
    }

    public function getPrimaryKey()
    {
        return $this->_mPrimaryKey;
    }

    public function setPrimaryKey($mKey)
    {
        $this->_mPrimaryKey = $mKey;
        return $this;
    }

    public function query()
    {
        if (!$this->_oQuery)
        {
            $this->_oQuery = new \APP\Engine\Database\Query();
        }
        return $this->_oQuery;
    }

    public function createRow($data = array())
    {
        $mRow = new \APP\Engine\Database\DbRow($this);
        if (!empty($this->getRowClass()))
        {
        	$mRow = system_cast_object($mRow, $this->getRowClass());
        }
        $mRow->setData($data);
        return $mRow;
    }

    public function getColumns()
    {
        $adapter = $this->getDatabase()->getAdapter();
        $results = $adapter->execute("SHOW COLUMNS FROM " . $this->_sTableName);

        $columns = array();
        foreach ($results as $result)
        {
            $field = $result['Field'];
            unset($result['Field']);
            $columns[$field] = $result;
        }
        return $columns;
    }

    public function executeQuery(\APP\Engine\Database\Query $query)
    {
        list($sSql, $aBindParams) = $query->build();
        return $this->getAdapter()->execute($sSql, $aBindParams);
    }

    public function createQuery($sType = "")
    {
        $oQuery = new \APP\Engine\Database\Query($sType);
        $oQuery->from($this->getTableName(), $this->getAlias());
        return $oQuery;
    }

    public function getTableName()
    {
        return $this->_sTableName;
    }

    public function getAlias()
    {
        if (!$this->_sAlias)
        {
            $this->_sAlias = $this->_sTableName;
        }
        return $this->_sAlias;
    }

    public function setAlias($sAlias)
    {
        $this->_sAlias = $sAlias;
        return $this;
    }

    public static function getFullTableName($sName)
    {
        $sPrefix = \APP\Engine\Application::getInstance()->getConfig('db', 'prefix');
        return $sPrefix . $sName;
    }

    public function getDatabase()
    {
        return \APP\Engine\Database::getInstance();
    }

    public function getAdapter()
    {
        return \APP\Engine\Database::getInstance()->getAdapter();
    }
    
	public function cleanCache($sPrefix = "")
	{
		if(empty($sPrefix))
		{
			$sPrefix = $this->getTableName(); 
		}
		Cache::getInstance()->getStorage()->clean("Model",$sPrefix);
	}
}
