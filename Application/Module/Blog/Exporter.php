<?php

namespace APP\Application\Module\Blog;

class Exporter extends \APP\Engine\Module\Exporter
{
	protected $_sFileName = "blog";
	protected $_aDatabaseTables = array (
			'blog' => array (
					'no-data' => true
			)
	);
}