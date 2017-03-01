<?php

namespace APP\Engine\HTML;

class Radio extends Checkbox
{

    public function __construct()
    {
        parent::__construct();
        $this->_sPatternHTML = '<input type="radio" %s #SELECTED# />';
    }

    public function render()
    {
        $sHTML = parent::getHTML();
        if ($this->_mDefaultValue && $this->value == $this->_mDefaultValue)
        {
            $sHTML = str_replace('#SELECTED#', 'checked', $sHTML);
        } else
        {
            $sHTML = str_replace('#SELECTED#', '', $sHTML);
        }
        return $sHTML;
    }

}
