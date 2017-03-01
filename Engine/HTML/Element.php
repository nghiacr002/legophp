<?php

namespace APP\Engine\HTML;

use APP\Engine\Object;
use APP\Engine\Validator;

class Element extends Object
{

    protected $_bRequired;
    protected $_mValidator;
    protected $_sPatternHTML;
    protected $_mDefaultValue;
    protected $_mValue;
    protected $_sInfoMessage;
    protected $_sName;
    protected $_sId;
    protected $_sErrorMessage;
	protected $_bForceValid = false;
	public function forceValid($mValue = true)
	{
		$this->_bForceValid = $mValue;
		return $this;
	}
	public function isForcedValidated()
	{
		return $this->_bForceValid;
	}
    public function required($bRequired = false)
    {
        $this->_bRequired = $bRequired;
        return $this;
    }

    public function isRequired()
    {
        return $this->_bRequired;
    }

    public function setId($sId)
    {
        $this->_sId = $sId;
        return $this;
    }

    public function getId()
    {
        return $this->_sId;
    }

    public function setName($sName)
    {
        $this->_sName = $sName;
        $this->setId($sName);
        return $this;
    }

    public function getName()
    {
        return $this->_sName;
    }

    public function setValue($mValue)
    {
        $this->_mValue = $mValue;
        return $this;
    }

    public function getValue()
    {
        if (is_string($this->_mValue))
        {
            return trim($this->_mValue);
        }
        return $this->_mValue;
    }

    public function setMessage($sMessage)
    {
        $this->_sInfoMessage = $sMessage;
        return $this;
    }

    public function getMessage()
    {
        if (!empty($this->_sErrorMessage))
        {
            return $this->_sErrorMessage;
        }
        return $this->_sInfoMessage;
    }

    public function setErrorMessage($sMessage)
    {
        $this->_sErrorMessage = $sMessage;
        return $this;
    }

    public function getErrorMessage()
    {
        return $this->_sErrorMessage;
    }

    public function language()
    {
        return $this->app()->language;
    }

    public function isValid()
    {
        $mValue = $this->getValue();
        if ($this->isRequired() && (empty($mValue) || $mValue == null))
        {
            $this->_sInfoMessage = $this->getId() . " required";
            return false;
        }
        if (!$this->_mValidator)
        {
            return true;
        }
        $bValid = $this->_mValidator->validate();
        if (!$bValid)
        {
            $this->_sInfoMessage = $this->_mValidator->errors($this->getName());
            if (is_array($this->_sInfoMessage))
            {
                $this->_sInfoMessage = implode("|", $this->_sInfoMessage);
            }
        }
        return $bValid;
    }

    public function validator(Validator $mValidator)
    {
        $this->_mValidator = $mValidator;
        return $this;
    }

    public function setAttrs($aAttrs = array())
    {
        foreach ($aAttrs as $sKey => $mValue)
        {
            $this->{$sKey} = $mValue;
        }
        return $this;
    }

    public function setAttr($sAttrName = "", $mValue = "")
    {
        $this->sAttrName = $mValue;
        return $this;
    }

    public function setDefaultValue($mValue)
    {
        $this->_mDefaultValue = $mValue;
        return $this;
    }

    public function hasRequired()
    {
        if ($this->_bRequired)
        {
            return $this->rawHTML('<span class="required">*</span>');
        }
        return "";
    }

    public function render()
    {
        $sText = $this->_sPatternHTML;
        $Attributes = $this->getProps();
        $sExtraText = "";
        if (count($Attributes))
        {
            $aProps = array();
            foreach ($Attributes as $sProp => $sValue)
            {
                $aProps[$sProp] = $sProp . ' = "' . $sValue . '"';
            }
            $sExtraText = implode(" ", $aProps);
        }
        $sText = sprintf($sText, $sExtraText);
        return $this->rawHTML($sText);
    }

    protected function rawHTML($sHTML)
    {
        return new \Twig_Markup($sHTML, "uft8");
    }

}
