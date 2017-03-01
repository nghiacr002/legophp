<?php

namespace APP\Engine\Module;

class Controller extends Component
{
    protected function preInitialize()
    {
        if (!empty($this->_sName))
        {
            return $this->_sName;
        }
        $this->_sName = substr(strrchr('\\' . get_class($this), '\\'), 1);
        if (($iLasIndex = strrpos($this->_sName, "Controller")))
        {
            $this->_sName = substr($this->_sName, 0, $iLasIndex);
        }
    }
    public function setActiveMenu($sMenuRouter)
    {
        $this->app->setSharedData('active-menu', $sMenuRouter);
    }
    public function request()
    {
        $request = \APP\Engine\Application::getInstance()->request;
        return ($request);
    }

    public function getViewPath()
    {
        $sTemplateName = $this->app->template->getTemplateName();
        $sPathView = APP_MODULE_PATH . $this->_sModule .
                APP_DS . "View" . APP_DS . $sTemplateName .
                APP_DS . "Controller" . APP_DS . $this->_sName . APP_DS;
        
        if ($sTemplateName != "Default" && !file_exists($sPathView))
        {
            $sPathView = APP_MODULE_PATH . $this->_sModule .
                    APP_DS . "View" . APP_DS . "Default" .
                    APP_DS . "Controller" . APP_DS . $this->_sName . APP_DS;
        }
        ($sPluginCode = Plugin::fetch('get_controller_path',true)) ? @eval($sPluginCode): "";
        return $sPathView;
    }
    public function getViewFile($sActionName)
    {
        $sPath = $this->getViewPath() . \APP\Engine\Template::getFileName(ucfirst($sActionName));
        ($sPluginCode = Plugin::fetch('get_controller_path_file_view',true)) ? @eval($sPluginCode): "";
        return $sPath;
    }
    public function getContent($sActionName)
    {
    	$sLayoutName = $this->view->getLayout(); 
        if ($sLayoutName)
        {
            $sActionName = $sLayoutName;
        }
        $this->view->getTemplate()->prependPath($this->getViewPath());
        $this->view->setParent($this);
        $sActionName = \APP\Engine\Template::getFileName(ucfirst($sActionName));
        return $this->view->getContent($sActionName);
    }

    public function setMenuActive($sMenuUrl)
    {
        $this->_sActiveMenuUrl = $sMenuUrl;
        return $this;
    }

}
