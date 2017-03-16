<?php

$_ROUTER['default'] = array(
    'blog_view_detail' => array(
        'route' => 'blog/[*:slug]-[i:id]',
        'controller' => 'index',
        'action' => 'view',
        'module' => 'blog',
        'params' => array(),
    ),
	'blog_category_detail' => array(
		'route' => 'blog/category/[*:slug]-[i:id]',
		'controller' => 'index',
		'action' => 'category',
		'module' => 'blog',
		'params' => array(),
	),
);
