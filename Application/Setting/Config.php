<?php

$_CONF['db'] = array(
    'host' => 'localhost',
    'name' => 'simplecms',
    'user' => 'root',
    'pwd' => '123456',
    'port' => 3306,
    'prefix' => 'tbl_',
    'adapter' => 'mysqli',
    'charset' => 'utf8'
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
$_CONF['app'] = array(
	'version' => '1',
	'name' => 'BeanVN',
	'release' => '', //time
	'build' => '',
);
$_CONF['cookie'] = array(
    'prefix' => 'simplecms_',
    'expried' => 30,
    'path' => '/',
    'domain' => '',
);
$_CONF['cache'] = array(
    'storage' => 'file',
    'path' => APP_CACHE_PATH,
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
    'css' => false,
    'js' => false,
    'file_size' => 256, //kb
);
$_CONF['gzip'] = array(
    'css' => false,
    'js' => false,
    'template' => false,
);
?>