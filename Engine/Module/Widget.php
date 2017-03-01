<?php

namespace APP\Engine\Module;

class Widget extends Controller
{
	protected $_sTitle;
	protected $_sContent;
	public function setTitle($sTitle)
	{
		$this->view->sTitle = $this->_sTitle = $sTitle;
		return $this;
	}
	public function setContent($sContent)
	{
		$this->view->sContent = $this->_sContent = $sContent;
		return $this;
	}
    public function getViewPath()
    {
        $sTemplateName = $this->app->template->getTemplateName();
        $sPathView = APP_MODULE_PATH . $this->_sModule .
                APP_DS . "View" . APP_DS . $sTemplateName .
                APP_DS . "Widget" . APP_DS;
        if ($sTemplateName != "Default" && !file_exists($sPathView))
        {
            $sPathView = APP_MODULE_PATH . $this->_sModule .
                    APP_DS . "View" . APP_DS . "Default" .
                    APP_DS . "Widget" . APP_DS;
        }
        return $sPathView;
    }

    public function process()
    {
        return null;
    }
    public function getContent($sActionName)
    {
    	$sId = $this->getId();
    	if(empty($sId))
    	{
    		$sId = "widget";
    	}
    	$sId = strtolower($sId);
    	$sId .= "-" . uniqid(APP_TIME);
    	$this->view->wid = $sId;
    	return parent::getContent($sActionName);
    }

}
