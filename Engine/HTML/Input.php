<?php

namespace APP\Engine\HTML;

class Input extends Element
{

    public function __construct()
    {
        parent::__construct();
        $this->_sPatternHTML = '<input type="text" %s />';
    }

}
