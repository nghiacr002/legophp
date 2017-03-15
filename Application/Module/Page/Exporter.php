<?php

namespace APP\Application\Module\Page;

class Exporter extends \APP\Engine\Module\Exporter
{
	protected $_sFileName = "page";
	protected $_aDatabaseTables = array (
		'page' => array (),
	);
	protected function _buildConditions($sTable)
	{
		$aConds = array();
		switch ($sTable)
		{
			case 'setting':
				$aConds[] = array('module',array('core','mail'),'IN');
				break;
			case 'widgets':
				$aConds[] = array('module_name',array('core','page'),'IN');
				break;
			case 'permission':
				$aConds[] = array('module',array('Core','Page','Theme','User'),'IN');
				break;
			case 'module':
				$aConds[] = array('module_name',array('core','page','theme','user'),'IN');
		}
		return $aConds;
	}
}