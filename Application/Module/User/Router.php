<?php

$_ROUTER['default'] = array(
	'user_profile_view' => array(
		'route' => '[*:slug]',
        'controller' => 'profile',
        'action' => 'view',
        'module' => 'user',
        'params' => array(),
	),
);