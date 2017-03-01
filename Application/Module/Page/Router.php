<?php

$_ROUTER['default'] = array(
    'page_view_detail' => array(
        'route' => '[*:slug]',
        'controller' => 'index',
        'action' => 'view',
        'module' => 'page',
        'params' => array(),
    ),
);
