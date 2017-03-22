<?php $_CONF = array (
  'db' => 
  array (
    'host' => 'localhost',
    'name' => '',
    'user' => '',
    'pwd' => '',
    'port' => 3306,
    'prefix' => 'tbl_',
    'adapter' => 'mysqli',
    'charset' => 'utf8',
    'type' => 'mysql',
  ),
  'api_versions' => 
  array (
    0 => 'default',
  ),
  'system' => 
  array (
    'data_response' => 'JSON',
    'format_time' => 'l jS \\of F Y h:i:s A',
    'base_path' => '/',
    'session_prefix' => 'legophp_',
    'admin_path' => 'admincp',
    'language' => 'en',
  ),
  'apps' => 
  array (
    'version' => '1.18.13032017',
    'name' => 'LEGO-BEAN',
  ),
  'cookie' => 
  array (
    'prefix' => 'legophp_',
    'expried' => 30,
    'path' => '/',
    'domain' => '',
  ),
  'cache' => 
  array (
    'storage' => 'file',
    'path' => '/var/www/html/simplecms/Public/Cache/',
  ),
  'redis' => 
  array (
    'host' => '127.0.0.1',
    'port' => 6379,
    'schema' => 'tcp',
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