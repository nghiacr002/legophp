<?php

namespace APP\Engine\Module;

use APP\Engine\Object;

class Helper extends Object
{
    protected $_aDataInMemory = array();
    public function callback($sFunction, $aParams = array(), $aForceModules = null, $bKeepInMemory = false)
    {
        if ($bKeepInMemory && isset($this->_aDataInMemory[$sFunction]))
        {
        	$oObject = $this->_aDataInMemory[$sFunction];
            return $oObject->{$sFunction}($aParams);
        }
        $aResults = array();
        if (!$aForceModules)
        {
            $aForceModules = array();
            $aInstalledModule = $this->app->module->getInstalledModules();
            foreach ($aInstalledModule as $iKey => $oModule)
            {
                if ($oModule->is_active != \APP\Engine\Module::ACTIVATE)
                {
                    continue;
                }
                $aForceModules[] = $oModule->module_name;
            }
        }
        if (count($aForceModules))
        {
            $aParts = explode('.', $sFunction);
            foreach ($aForceModules as $iKey => $sModuleName)
            {
                //$sModuleName = ucfirst($sModuleName);
                if(!$this->app->module->checkActive($sModuleName))
                {
                	continue;
                }
                $oBootstrap = Component::factory($sModuleName, "Bootstrap", "");
                if ($oBootstrap && method_exists($oBootstrap, $sFunction))
                {
                	if($bKeepInMemory)
                	{
                		$this->_aDataInMemory[$sFunction] = $oBootstrap;
                	}
                    $aResult = $oBootstrap->{$sFunction}($aParams);
                    if (isset($aParams['group_item']) && $aParams['group_item'] == true)
                    {
                        $aResults[$sModuleName] = $aResult;
                    } else
                    {
                        $aResults = array_merge($aResults, $aResult);
                    }
                }
            }
        }
        return $aResults;
    }
	public function getPlugin($sModuleName, $sPluginName)
	{
		$sFileName = APP_MODULE_PATH . ucfirst($sModuleName) . APP_DS . "Plugin" . APP_DS. $sPluginName.'.php';
		if(file_exists($sFileName))
		{
			require_once $sFileName;
			$oClass = new $sPluginName();
			return $oClass;
		}
		return null;
	}
}
