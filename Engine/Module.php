<?php

namespace APP\Engine;

use APP\Application\Module\Core\Model\Module as ModelModule;
use APP\Engine\Module\Component;

class Module
{

    const ACTIVATE = 1;
    const DEACTIVATE = 0;

    private $_aInstalledModules = null;
    private $_sCurrentController;
    private $_sCurrentAction;
    private $_sCurrentModule;
    private $_aAdminMenus = array();
    private $_aCurrentRouter = array();
	private $_oCurrentController = null;

    public function set($sModule = null, $sController = null, $sAction = null)
    {
        if ($sModule)
        {
            $this->_sCurrentModule = $sModule;
        }
        if ($sController)
        {
            $this->_sCurrentController = $sController;
        }
        if ($sAction)
        {
            $this->_sCurrentAction = $sAction;
        }
        return $this;
    }
	public function getInstanceController()
	{
		return $this->_oCurrentController;
	}
    public function getCurrentRouter()
    {
        return $this->_aCurrentRouter;
    }
    public function setInstanceController(&$oController)
    {
    	$this->_oCurrentController = $oController;
    	return $this;
    }
	public function getCurrentPathRouter()
	{
		return $this->_sCurrentModule .'.'.$this->_sCurrentController.'.'.$this->_sCurrentAction;
	}
    public function setRouter($aRouter)
    {
        $this->_aCurrentRouter = $aRouter;
        return $this;
    }

    public function getCurrentModule()
    {
        return $this->_sCurrentModule;
    }

    public function getCurrentController()
    {
        return $this->_sCurrentController;
    }

    public function getCurrentAction()
    {
        return $this->_sCurrentAction;
    }

    public function getInstalledModules()
    {
        if ($this->_aInstalledModules == null)
        {
            $this->_aInstalledModules = (new ModelModule())->getAll();
        }
        return $this->_aInstalledModules;
    }

    public function checkActive($sModuleName)
    {
        $aModules = $this->getInstalledModules();
        foreach ($aModules as $iKey => $oModule)
        {
            if ($oModule->is_active == self::ACTIVATE && strtolower($oModule->module_name) == strtolower($sModuleName))
            {
                return true;
            }
        }
        return false;
    }

    public function initialize()
    {
        $aModules = $this->getInstalledModules();
        if (is_array($aModules) && count($aModules))
        {
            foreach ($aModules as $iKey => $oModule)
            {
                if ($oModule->is_active != self::ACTIVATE)
                {
                    continue;
                }
                $oBootstrap = Component::factory($oModule->module_name, "Bootstrap", "");
                if ($oBootstrap)
                {
                    $oBootstrap->init();
                    if (\APP\Engine\Application::getInstance()->isAdminPanel())
                    {
                        $mResult = $oBootstrap->getAdminMenu();
                        if (is_array($mResult) && count($mResult))
                        {
                            $this->_aAdminMenus[] = $mResult;
                        }
                    }
                }
            }
        }
    }

    public function getAdminMenus()
    {
        return $this->_aAdminMenus;
    }

}
