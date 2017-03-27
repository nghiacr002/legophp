<?php

$_CONF['db'] = array(
    'host' => 'localhost',
    'name' => 'simplecms',
    'user' => 'root',
    'pwd' => '123456',
    'port' => 3306,
    'prefix' => 'tbl_',
    'adapter' => 'mysqli',
    'charset' => 'utf8',
	'type' => 'mysql',
);
$_CONF['api_versions'] = array(
    'default', '1.1',
);
$_CONF['system'] = array(
    'data_response' => 'JSON',
    'format_time' => 'l, d/m/Y h:i:s A',
    'base_path' => '/simplecms/',
    'session_prefix' => 'simplecms_',
    'admin_path' => 'admincp',
    'language' => 'en',
);
$_CONF['apps'] = array(
	'version' => '1.00.00000000',
	'name' => 'LEGO-BEAN'
);
$_CONF['cookie'] = array(
    'prefix' => 'simplecms_',
    'expried' => 30,
    'path' => '/',
    'domain' => '',
);
$_CONF['cache'] = array(
    'storage' => 'file',//file or redis
    'path' => APP_CACHE_PATH,
);
$_CONF['redis'] = array(
	'host' => '127.0.0.1',
	'port' => 6379,
	'schema' => 'tcp'
);
$_CONF['enviroment'] = 'development';
$_CONF['security'] = array(
    'random' => 'abqwertyuioplkjhgfdsaxcvbnm876543210'
);
$_CONF['template'] = array(
    'frontend' => 'Default',
    'backend' => 'Default',
    'prefix' => ".tpl",
);
$_CONF['minify'] = array(
    'css' => true,
    'js' => true,
    'file_size' => 256, //kb
);
/*
$_CONF['gzip'] = array(
    'css' => false,
    'js' => false,
    'template' => true,
	'level' => 3
);*/
?>