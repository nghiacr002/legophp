<?php

namespace APP\Engine\HTML;

class Textarea extends Element
{

    public function __construct()
    {
        parent::__construct();
        $this->_sPatternHTML = '<textarea></textarea>';
    }

}
