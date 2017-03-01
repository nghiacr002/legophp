<?php

namespace APP\Engine\Module;

use APP\Engine\Object;
use APP\Engine\File;
use APP\Engine\Cache;
use APP\Engine\Parse;

class Plugin extends Object
{
    public static function fetch($sName, $bReturn = false)
    {
        $oCache = (new \APP\Engine\Cache())->getStorage();
        $sCacheName = 'plugin_' . $sName;
        if (!($aPlugins = $oCache->get($sCacheName)) || defined('APP_NO_CACHE_PLUGIN'))
        {
            $oFile = new File();
            $aFolders = $oFile->scanDir(APP_MODULE_PATH, false);
            foreach ($aFolders as $iKey => $aFolder)
            {
                $sPluginName = $aFolder['full_path'] . APP_DS . "Plugin" . APP_DS . $sName . '.php';
                if (file_exists($sPluginName))
                {
                    $aPlugins[] = $sPluginName;
                }
            }
            $oCache->set($sCacheName, $aPlugins, 1000, "Plugin");
        }
        if (is_array($aPlugins) && count($aPlugins))
        {
	        if ($bReturn)
	        {
	        	$sCacheName = 'plugin_' . $sName.'_data';
	        	if (!($sPluginCode = $oCache->get($sCacheName)) || defined('APP_NO_CACHE_PLUGIN'))
	        	{
        			$sCode = "";
        			foreach ($aPlugins as $sPluginName)
        			{
        				$sCodePlugin = @file_get_contents($sPluginName);
        				$sCode .= $sCodePlugin;
        			}
        			$sPluginCode = $sCode;
	        		$oCache->set($sCacheName, $sPluginCode, 1000, "Plugin");
	        		
	        	}
	        	return '?>' .$sPluginCode;
	        }   
	        else
	        {
	        	foreach ($aPlugins as $sPluginName)
	        	{
	        		include_once $sPluginName;
	        	}
	        }
        }
        return "";
    }

}
