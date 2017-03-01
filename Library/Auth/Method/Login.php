<?php

namespace APP\Library\Auth\Method;

use APP\Library\Auth\AuthInterface;

class Login implements AuthInterface
{

    public function __construct($aUser = null)
    {
        
    }

    public function setParams($oUser = null)
    {
        
    }

    public function isAuthenticated()
    {
        return false;
    }

    public function unauthorized()
    {
        
    }

    public function getViewer()
    {
        return null;
    }

    public function getAcl()
    {
        return null;
    }

}
