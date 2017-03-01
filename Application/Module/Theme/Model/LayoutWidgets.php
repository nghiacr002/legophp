<?php

namespace APP\Application\Module\Theme\Model;

use APP\Engine\Module\Model;
use APP\Application\Module\Theme\Model\DbTable\LayoutWidgets as DbLayoutWidgets;
use APP\Engine\Database\Query as Query;

class LayoutWidgets extends Model
{

    protected $_sItemType = "";
    protected $_iItemId = 0;

    public function __construct()
    {
        $this->_oTable = new DbLayoutWidgets();
        parent::__construct();
    }

    public function setItem($iItemId, $sType)
    {
        $this->_sItemType = $sType;
        $this->_iItemId = $iItemId;
        return $this;
    }

    public function deleteByItem($iItemId, $sType)
    {
        $oQuery = $this->getTable()->createQuery("delete");
        $oQuery->from($this->getTable()->getTableName());
        $oQuery->where("item_id", $iItemId);
        $oQuery->where('item_type', $sType);
        return $oQuery->execute();
    }
	public function getByItem($iItemId, $sType ){
		$aConds = array(
			array('item_id', $iItemId),
			array('item_type', $sType),
		);
		$aWidgets = $this->getAll($aConds,null,null,"*",array('ordering','ASC'));
		return $aWidgets;
	}
    public function getByLocation($iLocationId = 0, $iLayoutId = 0)
    {
        $aConds = array(
            array('location_id', $iLocationId),
            array('layout_id', $iLayoutId),
        );
        if (!empty($this->_iItemId) && $this->_iItemId > 0)
        {
            $aConds[] = array('item_id', $this->_iItemId);
        }
        if (!empty($this->_sItemType))
        {
            $aConds[] = array('item_type', $this->_sItemType);
        }
        $aWidgets = $this->getAll($aConds,null,null,"*",array('ordering','ASC'));
        return $aWidgets;
    }

    public function getAll($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "layout_widget.*", $mOrder = null)
    {
        $oQuery = new Query("select");
        $oQuery->from($this->_oTable->getTableName(), 'layout_widget');
        $sJoinedTable = \APP\Engine\Database\DbTable::getFullTableName('widgets');
        $oQuery->join($sJoinedTable, 'widgets.widget_id = layout_widget.widget_id ', "widgets");
        $oQuery->select('layout_widget.*,widget_name,widget_router');
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
            $mResult['widget_name'] = $this->app()->language->translate($mResult['widget_name']);
            if (!empty($mResult['params']))
            {
                $mResult['params'] = @json_decode($mResult['params'], true);
            }
            if (!empty($mResult['param_values']))
            {
                $mResult['param_values'] = @json_decode($mResult['param_values'],true);
            }
            else
            {
            	$mResult['param_values'] = array();
            }
            $oRow->setData($mResult);

            if (!empty($this->_oTable->getRowClass()))
            {
                $oRow = system_cast_object($oRow, $this->_oTable->getRowClass());
            }
            $mRows[] = $oRow;
        }
        return $mRows;
    }

}
