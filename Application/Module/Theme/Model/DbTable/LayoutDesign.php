<?php 
namespace APP\Application\Module\Theme\Model\DbTable;
class LayoutDesign extends \APP\Engine\Database\DbTable
{
	protected $_sTableName = "layout_design";
	protected $_mPrimaryKey = "pw_id";
	protected $_aValidateRules = array(
		'required' => array(
			array('layout_id'),array('location_id'),array('widget_id')
		)
	);
	protected $_sRowClass = "\APP\Application\Module\Theme\Model\DbTable\DbRow\LayoutDesign";
}
