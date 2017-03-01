<?php

namespace APP\Engine\HTML;

class Select extends Element
{

    protected $_aOptions = array();

    public function __construct()
    {
        parent::__construct();
        $this->_sPatternHTML = '<select  %s />';
    }

    public function setOption($mKey, $sName, $aSubCats = array())
    {
        if (count($aSubCats))
        {
            $this->_aOptions[$mKey] = array(
                'name' => $sName,
                'sub' => $aSubCats
            );
        } else
        {
            $this->_aOptions[$mKey] = $sName;
        }
        return $this;
    }

    public function getOptions()
    {
        return $this->_aOptions;
    }

}
