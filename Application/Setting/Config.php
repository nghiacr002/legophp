<?php $_CONF = array (
  'db' => 
  array (
    'host' => 'localhost',
    'name' => 'simplecms',
    'user' => 'root',
    'pwd' => '123456',
    'port' => '3306',
    'prefix' => 'tbl_',
    'charset' => 'utf8',
    'adapter' => 'mysqli',
  ),
  'api_versions' => 
  array (
    0 => 'default',
    1 => '1.1',
  ),
  'system' => 
  array (
    'data_response' => 'JSON',
    'format_time' => 'l jS \\of F Y h:i:s A',
    'base_path' => '/legophp/',
    'session_prefix' => 'simplecms_',
    'admin_path' => 'admincp',
    'language' => 'en',
  ),
  'cookie' => 
  array (
    'prefix' => 'simplecms_',
    'expried' => 30,
    'path' => '/',
    'domain' => '',
  ),
  'cache' => 
  array (
    'storage' => 'file',
    'path' => '/Users/Nghia/Documents/php/legophp/Public/Cache/',
  ),
  'enviroment' => 'development',
  'security' => 
  array (
    'random' => 'abqwertyuioplkjhgfdsaxcvbnm876543210',
  ),
  'template' => 
  array (
    'frontend' => 'Default',
    'backend' => 'Default',
    'prefix' => '.tpl',
  ),
  'minify' => 
  array (
    'css' => false,
    'js' => false,
    'file_size' => 256,
  ),
  'gzip' => 
  array (
    'css' => false,
    'js' => false,
    'template' => false,
  ),
		
)?>
  <?php
  $_CONF['apps'] = array(
  		'version' => '1.00.00000000',
  		'name' => 'LEGO-BEAN'
  );
  ?>