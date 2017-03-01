<?php

namespace APP\Engine\HTML;

use APP\Engine\File as FileManager;

class File extends Element
{

    protected $_aAllowFileTypes = array();
    private $_aFileInfo = array();
    private $_iFileSize = 0;

    public function __construct()
    {
        parent::__construct();
        $this->_sPatternHTML = '<input type="file" %s />';
        $this->_iFileSize = (new FileManager())->getMaximumFileUploadSize();
    }

    public function setMaxFileSize($iFileSize)
    {
        $this->_iFileSize = $iFileSize;
        return $this;
    }

    public function isValid()
    {
    	if($this->isForcedValidated())
    	{
    		return true;
    	}
        $aFile = isset($_FILES[$this->getName()]) ? $_FILES[$this->getName()] : array();

        if (isset($aFile['name'])  && !empty($aFile['name']))
        {
            return true;
        }
        return false;
    }

    public function getMaxFileSize()
    {
        return $this->_iFileSize;
    }

    public function setFileType($sFileType = null)
    {
        if (is_array($sFileType))
        {
            $this->_aAllowFileTypes = array_merge($this->_aAllowFileTypes, $sFileType);
        } else
        {
            $this->_aAllowFileTypes[] = $sFileType;
        }
        return $this;
    }

    public function validateFileType()
    {
        $aFile = isset($_FILES[$this->getName()]) ? $_FILES[$this->getName()] : array();
        if (!isset($aFile['name']) || empty($aFile['name']))
        {
            return false;
        }
        if (in_array("*", $this->_aAllowFileTypes))
        {
            return true;
        }
        $sType = (new FileManager())->getExt($aFile['name']);
        return in_array($sType, $this->_aAllowFileTypes);
    }

}
