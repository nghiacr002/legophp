<?php

namespace APP\Application\Module\Theme\Model;

use APP\Application\Module\Theme\Model\DbTable\Layout as DbLayout;
use APP\Engine\Module\Model;
use APP\Engine\Database\Query;
class Layout extends Model
{

    public function __construct()
    {
        $this->_oTable = new DbLayout();
        parent::__construct();
    }
	
	public function getLayoutPath()
	{
		return APP_THEME_PATH . 'PageLayout' . APP_DS;	
	}
	public function setDefault($iId)
	{
		//set off all
		$oQuery = new Query("update");
		$oQuery->setTableData($this->getTable()->getTableName(), array(
			'is_template_default' => 0,
		));
		$bResult1 = $this->getTable()->executeQuery($oQuery);
		$oQuery->clean();
		$oQuery->where('layout_id', $iId);
		$oQuery->setTableData($this->getTable()->getTableName(), array(
			'is_template_default' => 1,
		));
		$bResult2 = $this->getTable()->executeQuery($oQuery);
		return $bResult2;
	}
	
}
