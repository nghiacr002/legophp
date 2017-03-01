<?php

namespace APP\Library\Auth\Method;

use APP\Library\Auth;

class Basic implements Auth
{

    protected $_aParams = array();

    public function __construct($aParams = array())
    {
        $this->_aParams = $aParams;
    }

    public function isAuthenticated()
    {
        return true;
    }

}
