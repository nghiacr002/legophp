<?php 
namespace APP\Application\Module\Page\Model\DbTable;
class Layout extends \APP\Engine\Database\DbTable
{
	protected $_sTableName = "page_layout";
	protected $_mPrimaryKey = "layout_id";
	protected $_aValidateRules = array(
		'required' => array(
			array('layout_title'),array('layout_name')
		)
	);
	protected $_sRowClass = "\APP\Application\Module\Page\Model\DbTable\DbRow\Layout";
}
