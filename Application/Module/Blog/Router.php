<?php

$_ROUTER['default'] = array(
    'blog_view_detail' => array(
        'route' => 'blog/[*:slug]-[i:id]',
        'controller' => 'index',
        'action' => 'view',
        'module' => 'blog',
        'params' => array(),
    ),
);
