<?php 
namespace APP\Application\Module\Page\Model;
use APP\Engine\Module\Model;
use APP\Application\Module\Page\Model\DbTable\PageWidgets as DbPageWidgets;
use APP\Engine\Database\Query as Query;
class PageWidgets extends Model
{
	public function __construct()
	{
		$this->_oTable = new DbPageWidgets();
		parent::__construct();
	}
	public function getByPage($iPageId)
	{
		return $this->getOne($iPageId,'page_id');
	}
	public function deletebyPage($iPageId)
	{
		$oQuery = $this->getTable()->createQuery('delete');
		$oQuery->where('page_id',$iPageId);
		$oQuery->execute();
	}
	public function getByLocation($iPageId, $iLocationId  = 0)
	{
		$aConds = array(
				array('page_id',$iPageId),
				array('location_id',$iLocationId)
		);
		$aPageWidgets = $this->getAll($aConds);
		return $aPageWidgets;
	}
	public function getAll($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "page_widget.*", $mOrder = null)
	{
		$oQuery = new Query("select");
		$oQuery->from($this->_oTable->getTableName(),'page_widget');
		$sJoinedTable = \APP\Engine\Database\DbTable::getFullTableName('widgets');
		$oQuery->join($sJoinedTable,
				'widgets.widget_id = page_widget.widget_id ',"widgets");
		$oQuery->select('page_widget.*,widget_name,widget_router,widgets.params as default_params,widget_type');
		$oQuery->limit($iPage, $iLimit);
		if(count($mOrder) == 2)
		{
			$oQuery->order($mOrder[0],$mOrder[1]);
		}
		if(count($aConds))
		{
			foreach($aConds as $iKey => $aCond)
			{
				$param = isset($aCond[0]) ? $aCond[0] : "";
				$bind = isset($aCond[1]) ? $aCond[1] : "";
				$operator = isset($aCond[2]) ? $aCond[2] : "=";
				$cond_type = isset($aCond[3]) ? $aCond[3] : "AND";
				$oQuery->where($param,$bind,$operator,$cond_type);
			}
		}
		$mResults = $this->_oTable->executeQuery($oQuery);
		if(!isset($mResults[0]))
		{
			return null;
		}
		$mRows = array();
		foreach($mResults as $iKey => $mResult)
		{
			$oRow = new  \APP\Engine\Database\DbRow($this->_oTable);
			$mResult['widget_name'] = $this->app()->language->translate($mResult['widget_name']);
			if(!empty($mResult['params']))
			{
				$mResult['params'] = unserialize($mResult['params']);
			}
			if(!empty($mResult['default_params']))
			{
				$mResult['default_params'] = unserialize($mResult['default_params']);
			}
			$oRow->setData($mResult);
				
			if(!empty($this->_oTable->getRowClass()))
			{
				$oRow = system_cast_object($oRow, $this->_oTable->getRowClass());
			}
			$mRows[] = $oRow;
		}
		return $mRows;
	}
}
