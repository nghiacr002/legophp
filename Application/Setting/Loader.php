<?php

use APP\Engine;

if (!ini_get('date.timezone'))
{
    date_default_timezone_set('GMT');
}
require 'Constant.php';
if (file_exists(APP_PATH_SETTING . 'Config.php'))
{
	define('HAS_CONFIG_FILE',true);
    require 'Config.php';
}
else
{
	define('HAS_CONFIG_FILE',false);
    require 'Config.default.php';
}
require_once APP_PATH_LIB . 'vendor' . APP_DS . 'autoload.php';
spl_autoload_extensions(".php");
spl_autoload_register('system_autoloader');

function d($mInfo, $bVarDump = false)
{
    $bCliOrAjax = (PHP_SAPI == 'cli');
    (!$bCliOrAjax ? print '<pre style="text-align:left; padding-left:15px;">' : false);
    ($bVarDump ? var_dump($mInfo) : print_r($mInfo));
    (!$bCliOrAjax ? print '</pre>' : false);
}
function system_gzhandler($sContent){
	$sCompressedContent = $sContent;
	if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])
			&& strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false && function_exists('gzcompress'))
	{
		$iLength = strlen($sContent);
		if ($iLength > 1000)
		{
			header("Content-Encoding: gzip");
			$sCompressedContent = gzcompress($sContent);
			$sCompressedContent = substr($sCompressedContent, 0, strlen($sCompressedContent) - 4); // Why cut off ??
			$sCRC = crc32($sContent);
			$sCompressedContent = "\x1f\x8b\x08\x00\x00\x00\x00\x00" .$sCompressedContent .pack('V', $sCRC) . pack('V', $iLength);;
		}
	}
	return $sCompressedContent;
}

function system_autoloader($class)
{
    $prefix = 'APP\\';
    // base directory for the namespace prefix
    $base_dir = APP_PATH;
    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0)
    {
        // no, move to the next registered autoloader
        return;
    }
    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = APP_ROOT_PATH . str_replace('\\', '/', $relative_class) . '.php';
    // if the file exists, require it
    if (file_exists($file))
    {
        require $file;
    }
}

function system_include_path($sPath)
{
    if (file_exists($sPath))
    {
        @set_include_path($sPath);
        @ini_set('include_path', $sPath);
        require_once $sPath;
        return true;
    }
    return false;
}

function system_load_version($sName = "", $sType = "controller", $sVersion = "default")
{
    $sPruralType = ucfirst($sType) . "s";
    if ($sVersion == "default")
    {
        $sPath = API_PATH . "Application" . API_DS;
        $sNamespace = "\\APP\\Application\\" . $sPruralType . "\\";
    } else
    {
        $sPath = API_PATH . "Version" . API_DS . "V" . str_replace(".", "", $sVersion) . API_DS;
        $sNamespace = "\\APP\\Version\\" . "V" . str_replace(".", "", $sVersion) . "\\" . $sPruralType . "\\";
    }
    $sFileName = $sPath . $sPruralType . API_DS . ucfirst($sName) . ucfirst($sType) . ".php";

    if (file_exists($sFileName))
    {
        require_once $sFileName;
        $sClassName = ucfirst($sName) . ucfirst($sType);
        $sFullClassName = $sNamespace . $sClassName;
        $oClass = new $sFullClassName();
        return $oClass;
    } elseif ($sVersion != "default")
    {

        return system_load_version($sName, $sType, "default");
    }
    return false;
}

function system_display_result($mResult = array())
{
    if (!is_array($mResult))
    {
        echo $mResult;
        exit;
    }
    $oResponse = new \APP\Engine\Response();
    if (isset($mResult['code']))
    {
        $oResponse->setCode($mResult['code']);
        unset($mResult['code']);
    } else
    {
        $oResponse->setCode(HTTP_CODE_OK);
    }
    if (isset($mResult['message']))
    {
        $oResponse->setMessage($mResult['message']);
    }
    if (isset($mResult['content_type']))
    {
        $oResponse->setContentType($mResult['content_type']);
    }
    $oResponse->setParams($mResult);
    $oResponse->display();
}

function system_cast_object($source, $dest)
{
    if (empty($dest) || !class_exists($dest))
    {
        return $source;
    }

    return unserialize(
            preg_replace(
                    '/^O:\d+:"[^"]++"/', 'O:' . strlen($dest) . ':"' . $dest . '"', serialize($source)
            )
    );
}

function array_to_xml(array $arr, SimpleXMLElement $xml)
{
    foreach ($arr as $k => $v)
    {
        is_array($v) ? array_to_xml($v, $xml->addChild($k)) : $xml->addChild($k, $v);
    }
    return $xml;
}

if (!function_exists('mime_content_type'))
{

    function mime_content_type($filename)
    {

        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',
            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',
            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.', $filename)));
        if (array_key_exists($ext, $mime_types))
        {
            return $mime_types[$ext];
        } elseif (function_exists('finfo_open'))
        {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        } else
        {
            return 'application/octet-stream';
        }
    }

}