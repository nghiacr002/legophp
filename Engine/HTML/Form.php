<?php

namespace APP\Engine\HTML;

class Form extends Element
{

    protected $_aElements = array();
    protected $_aMessages = null;
    protected $_sFormTemplate = null;

    public function __construct()
    {
        parent::__construct();
        $this->_sPatternHTML = '<form %s > %s </form>';
        $this->_aData = $this->app()->request->getParams();
    }

    public function request()
    {
        return $this->app()->request;
    }

    public function setTemplate($sTemplate = "")
    {
        $this->_sFormTemplate = $sTemplate;
        return $this;
    }

    public function getTemplate()
    {
        if (!$this->_sFormTemplate)
        {
            $sTemplateName = $this->app->template->getTemplateName();
            $this->_sFormTemplate = get_called_class();
            $sFormTemplatePath = "\\View\\" . $sTemplateName . '\\Form\\';
            $this->_sFormTemplate = str_replace('\\Form\\', $sFormTemplatePath, $this->_sFormTemplate);
            $this->_sFormTemplate = str_replace('\\', '/', $this->_sFormTemplate);
            $this->_sFormTemplate = str_replace('APP/Application/Module/', APP_MODULE_PATH, $this->_sFormTemplate);
            if (!file_exists($this->_sFormTemplate))
            {
                $this->_sFormTemplate = str_replace('/View/' . $sTemplateName . '/Form/', '/View/Default/Form/', $this->_sFormTemplate);
            }
        }
        return $this->_sFormTemplate;
    }

    public function addElement(Element $oElement)
    {
        if (isset($this->_aData[$oElement->getId()]))
        {
            $oElement->setValue($this->_aData[$oElement->getId()]);
        }
        $this->_aElements[$oElement->getId()] = $oElement;
        return $this;
    }
	public function removeElement($sId)
	{
		if(isset($this->_aElements[$sId]))
		{
			unset($this->_aElements[$sId]);
		}
		return $this;
	}
    public function isValid()
    {
        $bValid = true;
        foreach ($this->_aElements as $iKey => $oElement)
        {
            $bElementValid = $oElement->isValid();
            if (!$bElementValid)
            {
                $this->_aMessages[$oElement->getId()] = $oElement->getMessage();
            }
            $bValid &= $oElement->isValid();
        }
        return $bValid;
    }

    public function getMessages()
    {
        return $this->_aMessages;
    }

    public function getMessage()
    {
        $aMessages = $this->getMessages();
        return isset($aMessages[0]) ? $aMessages[0] : "";
    }

    public function getElements()
    {
        return $this->_aElements;
    }

    public function setFormValues($aData = array())
    {
        foreach ($this->_aElements as $iKey => $oElement)
        {
            if (isset($aData[$oElement->getId()]))
            {
                $oElement->setValue($aData[$oElement->getId()]);
            }
        }
    }

    public function getFormValues()
    {
        $aData = array();
        foreach ($this->_aElements as $iKey => $oElement)
        {
            $aData[$oElement->getName()] = $oElement->getValue();
        }
        return $aData;
    }

    public function render($bReturn = false)
    {
        $sTemplateFile = $this->getTemplate();
        $sTemplateFile = $this->app->template->getFileName($sTemplateFile);
        if (!file_exists($sTemplateFile))
        {
            return $this->getHTML();
        }
        $mContent = $this->app->template->assign(
                        array(
                            'form' => $this
                        )
                )->render($sTemplateFile, $bReturn);
        if ($bReturn)
        {
            return $mContent;
        }
        echo $mContent;
    }

    public function start($aParams = array())
    {
        $sAtribute = array();
        foreach ($aParams as $sProp => $sValue)
        {
            $sAtribute[$sProp] = $sProp . ' = "' . $sValue . '"';
        }
        $sAtribute = implode(" ", $sAtribute);
        return $this->rawHTML('<form ' . $sAtribute . '>');
    }

    public function end()
    {
        return $this->rawHTML('</form>');
    }

    protected function getHTML()
    {
        $sHTML = "";
        return $sHTML;
    }

    public function element($sName)
    {
        $oElement = isset($this->_aElements[$sName]) ? $this->_aElements[$sName] : null;
        return $oElement;
    }

    public function __get($sName)
    {
        $oElement = isset($this->_aElements[$sName]) ? $this->_aElements[$sName] : null;
        if ($oElement)
        {
            return $oElement;
        }
        return parent::_get($sName);
    }

}
