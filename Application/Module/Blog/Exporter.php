<?php

namespace APP\Application\Module\Blog;

class Exporter extends \APP\Engine\Module\Exporter
{
	protected $_sFileName = "blog";
	protected $_aDatabaseTables = array (
			'blog' => array (
				'no-data' => true
			),
			'setting' => array('no-structure' => true),
			'widgets' => array('no-structure' => true),
			'permission' => array('no-structure' => true),
			'module' => array('no-structure' => true),
			'module_controller' => array('no-structure' => true)
	);
	protected function _buildConditions($sTable)
	{
		$aConds = array();
		switch ($sTable)
		{
			case 'setting':
				$aConds[] = array('module',array('blog'),'IN');
				break;
			case 'widgets':
				$aConds[] = array('module_name',array('blog'),'IN');
				break;
			case 'permission':
				$aConds[] = array('module',array('Blog'),'IN');
				break;
			case 'module':
				$aConds[] = array('module_name',array('blog'),'IN');
				break;
			case 'module_controller':
				$aConds[] = array('module_name',array('blog'),'IN');
				break;
		}
		return $aConds;
	}
}