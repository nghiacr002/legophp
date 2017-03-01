<?php 
namespace APP\Application\Module\Page\Model\DbTable;
class PageWidgets extends \APP\Engine\Database\DbTable
{
	protected $_sTableName = "page_widgets";
	protected $_mPrimaryKey = "pw_id";
	protected $_aValidateRules = array(
		'required' => array(
			array('page_id'),array('widget_name'),array('location_id')
		)
	);
	protected $_sRowClass = "\APP\Application\Module\Page\Model\DbTable\DbRow\PageWidgets";
}
