<?php

namespace APP\Application\Module\Core;

class Exporter extends \APP\Engine\Module\Exporter
{
	protected $_sFileName = "core";
	protected $_aDatabaseTables = array (
			'category' => array (),
			'country' => array (),
			'hashtag' => array (
					'no-data' => true
			),
			'hashtag_stats' => array (
					'no-data' => true
			),
			'hook' => array (),
			'item_ordering' => array (
					'no-data' => true
			),
			'language' => array (),
			'language_patch' => array (),
			'layout' => array (),
			'layout_design' => array (),
			'layout_widgets' => array (),
			'media' => array ('no-data' => true),
			'meta_tags' => array (
					'no-data' => true
			),
			'menu' => array ('no-data' => true),
			'module' => array (),
			'module_controller' => array ('no-data' => true),
			'note' => array (),
			'notification' => array (
					'no-data' => true
			),
			'page' => array ('no-data' => true),
			'permission' => array (),
			'request_token' => array ('no-data' => true),
			'seo_data' => array (
					'no-data' => true
			),
			'setting' => array (),
			'system_stats' => array ('no-data' => true),
			'theme' => array (),
			'user' => array (
				'no-data' => true,
			),
			'user_configuration' => array ('no-data' => true),
			'user_group' => array ('no-data' => true),
			'user_group_permission' => array ('no-data' => true),
			'widgets' => array (
					'no-data' => true
			)
	);
}