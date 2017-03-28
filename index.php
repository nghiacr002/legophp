<?php
use APP\Engine\AppException;
use APP\Engine\Logger;

@error_reporting(E_ALL);
@ini_set('display_errors', -1);
if (version_compare(PHP_VERSION, '5.3.0', '<'))
{
    throw new Exception('Requires PHP version 5.3 or higher.');
}
define('SIMPLE_APP', true);
define('APP_DS', DIRECTORY_SEPARATOR);
define('APP_ROOT_PATH', dirname(__FILE__) . APP_DS);
define('APP_PATH', APP_ROOT_PATH . "Application" . APP_DS);
define('APP_PATH_SETTING', APP_PATH . 'Setting' . APP_DS);
define('APP_PATH_LIB', APP_ROOT_PATH . 'Library' . APP_DS);
require_once APP_PATH_SETTING . 'Loader.php';
ob_start('system_gzhandler');
if (!session_id())
{
    session_start();
}
try
{

    if(HAS_CONFIG_FILE == false){
    	header('Location:install.php');
    	exit;
    }
    $mainApp = new APP\Engine\Application($_CONF);
    $mainApp->execute();
} catch (Exception $ex)
{
    if ($mainApp instanceof APP\Engine\Application)
    {
        if ($mainApp->isAjaxCall())
        {
            system_display_result(array(
                'code' => $ex->getCode(),
                'message' => $ex->getMessage(),
            ));
        }
    }
    if (($ex instanceof AppException))
    {
        $sSystemCode = $ex->hashCode();
    } else
    {
        $sSystemCode = (new AppException())->hashCode();
    }
    $sTrace = $ex->getTraceAsString();
    $mDisplay = array(
        'code' => $ex->getCode(),
        'message' => $ex->getMessage(),
        'trace' => $sTrace,
        'system_code' => $sSystemCode,
    );
    if ($ex->getCode() == HTTP_CODE_NOT_FOUND)
    {
        $sTemplateErrorCode = "Notfound";
    } else
    {
        $sTemplateErrorCode = "Error";
    }
    Logger::error($ex, $sSystemCode);
    if (APP\Engine\Application::getInstance()->template)
    {
        echo
                APP\Engine\Application::getInstance()->template->
                setParams($mDisplay)->render($sTemplateErrorCode);
    } else
    {
        system_display_result($mDisplay);
    }
}
ob_end_flush();
?>