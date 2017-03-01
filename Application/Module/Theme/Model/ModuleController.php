<?php 
namespace APP\Application\Module\Theme\Model;
use APP\Engine\Module\Model;
use APP\Application\Module\Theme\Model\DbTable\ModuleController as DbModuleController;
use APP\Engine\Database\Query;
class ModuleController extends Model
{
	public function __construct()
	{
		$this->_oTable = new DbModuleController();
		parent::__construct();
	}
	public function getAll($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "module_controller.*", $mOrder = null)
    {
    	$oQuery = new Query("select");
    	$oQuery->from($this->_oTable->getTableName(),'module_controller');
    	$sJoinedTable = \APP\Engine\Database\DbTable::getFullTableName('layout');
    	$oQuery->join($sJoinedTable,
    			'layout.layout_id = module_controller.layout_id ',"layout","LEFT");
    	$oQuery->select('module_controller.*,layout.layout_title');
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
