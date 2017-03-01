<?php

namespace APP\Engine\Module;

use APP\Engine\Object;

class Bootstrap extends Object
{

    protected $_sModuleName = "";

    public function setModule($sModule)
    {
        $this->_sModuleName = $sModule;
        return $this;
    }

    public function setName()
    {
        return $this;
    }

    public function init()
    {
        $this->initSetting();
        $this->initLanguage();
        $this->initRouter();
        $this->initPlugin();
        $this->initTemplate();
        $this->subscribeEvent();
    }

    public function getAdminMenu()
    {
        return false;
    }

    protected function initSetting()
    {
        
    }

    protected function initLanguage()
    {
        $sLanguage = $this->app->language->getCurrentLanguage();
        $sFileName = APP_MODULE_PATH . $this->_sModuleName . APP_DS . "Language" . APP_DS . strtolower($sLanguage) . ".php";
        if (file_exists($sFileName))
        {
            include_once $sFileName;
            if (isset($aPhrases))
            {
                $this->app->language->appendPhrases($aPhrases);
            }
        }
    }

    protected function initRouter()
    {
        $this->app->router->registerRouter($this->_sModuleName);
    }

    protected function initTemplate()
    {
        return true;
    }

    protected function initPlugin()
    {
        return true;
    }
    protected function subscribeEvent()
    {
    	return true;
    }

}
