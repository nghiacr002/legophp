<?php

$_ROUTER['default'] = array(
    'user_update' => array(
        'route' => '/user/[i:id]/update',
        'controller' => 'user',
        'action' => 'update',
        'method' => 'PUT',
        'module' => 'user',
        'params' => array(),
    ),
);
