<?php

namespace APP\Application\Module\Blog\Model;

use APP\Engine\Module\Model;
use APP\Application\Module\Blog\Model\DbTable\Blog as DbBlog;
use APP\Engine\Database\Query;
use APP\Application\Module\Blog\Model\DbTable\DbRow\Blog as BlogRow;

class Blog extends Model
{

    const STATUS_ACTIVATED = 1;
    const STATUS_DRAFT = -2;
    const STATUS_DEACTIVATED = -1;

    public function __construct()
    {
        $this->_oTable = new DbBlog();
        parent::__construct();
    }
	public function getFeaturedItems($iLimit = 5)
	{
		$aConds = array(
			array('is_featured',1)
		);
		return $this->getAll($aConds,0,$iLimit);
	}
    public function getAll($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "*", $mOrder = null)
    {
        $sJoinedTableCategory = \APP\Engine\Database\DbTable::getFullTableName('category');
        $sJoinedTableUser = \APP\Engine\Database\DbTable::getFullTableName('user');
        $oQuery = new Query("select");
        $oQuery->from($this->_oTable->getTableName(), $this->getTable()->getAlias());
        $oQuery->join($sJoinedTableCategory, 'category.category_id = ' . $this->getTable()->getAlias() . '.category_id AND category.category_type="blog" ', "category");
        $oQuery->join($sJoinedTableUser, 'user.user_id = ' . $this->getTable()->getAlias() . '.owner_id', "user");
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
            $oRow = new BlogRow($this->getTable());
            $oRow->setData($mResult);
            $mRows[] = $oRow;
        }
        return $mRows;
    }

    public function getOne($mValue, $mTableKey = null, $sSelectFields = "*")
    {
        if (!$mTableKey)
        {
            $mTableKey = $this->_oTable->getPrimaryKey();
        }
        $oQuery = new Query("select");
        $sCategoryNameTable = \APP\Engine\Database\DbTable::getFullTableName("category");
        $oQuery->join($sCategoryNameTable, 'cat.category_id = ' . $this->getTable()->getAlias() . '.category_id', 'cat');
        $oQuery->select($sSelectFields);
        $oQuery->from($this->_oTable->getTableName(), $this->getTable()->getAlias());
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
	public function onDeleteBlog($aParams = array())
	{

	}
}
