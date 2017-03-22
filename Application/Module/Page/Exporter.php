<?php

namespace APP\Application\Module\Page;

class Exporter extends \APP\Engine\Module\Exporter
{
	protected $_sFileName = "page";
	protected $_aDatabaseTables = array (
		'page' => array ('no-data' => true),

	);
	protected function _buildConditions($sTable)
	{
		$aConds = array();

		return $aConds;
	}
}