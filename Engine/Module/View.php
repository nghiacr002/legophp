<?php

namespace APP\Engine\Module;

use APP\Engine\Object;

class View extends Object
{

    protected $_oParent;
    private $_oTemplate;
    private $_sLayoutName;

    public function __construct()
    {
        parent::__construct();
        $this->_oTemplate = \APP\Engine\Application::getInstance()->template;
        $this->_sLayoutName = null;
    }

    public function setLayout($sLayoutName)
    {
        $this->_sLayoutName = $sLayoutName;
        return $this;
    }

    public function getLayout()
    {
        return $this->_sLayoutName;
    }

    public function setParent($oParent)
    {
        $this->_oParent = $oParent;
        return $this;
    }

    public function getParent()
    {
        return $this->_oParent;
    }

    public function getContent($sName, $aParams = array(), $bReturnContent = true)
    {
        if (!is_array($this->_aData))
        {
            $this->_aData = array($this->_aData);
        }
        $aParams = array_merge($aParams, $this->_aData);

        return $this->_oTemplate->assign($aParams)->render($sName, $bReturnContent);
    }

    public function getTemplate()
    {
        return $this->_oTemplate;
    }

}
