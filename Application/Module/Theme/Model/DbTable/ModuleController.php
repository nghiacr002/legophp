<?php 
namespace APP\Application\Module\Theme\Model\DbTable;
class ModuleController extends \APP\Engine\Database\DbTable
{
	protected $_sTableName = "module_controller";
	protected $_mPrimaryKey = "controller_id";
	protected $_aValidateRules = array(
		'required' => array(
			array('controller_name'),array('router_name'),array('module_name'),array('layout_id'),
		)
	);
	protected $_sRowClass = "\APP\Application\Module\Theme\Model\DbTable\DbRow\ModuleController";
}
