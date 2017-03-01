<?php

namespace APP\Engine;

use APP\Library\Auth\AuthInterface as AuthInterface;

class Auth
{

    protected $_oMethod;

    public function __construct(AuthInterface $oMethod = null)
    {
        $this->_oMethod = $oMethod;
    }

    public function isAuthenticated()
    {
        return $this->_oMethod->isAuthenticated();
    }

    public function unauthorized()
    {
        return $this->_oMethod->unauthorized();
    }

    public function getViewer()
    {
        return $this->_oMethod->getViewer();
    }

    public function setMethod(AuthInterface $oMethod)
    {
        $this->_oMethod = $oMethod;
        return $this;
    }

    public static function getToken()
    {
        return uniqid(time()) . md5(time());
    }

    public function getMethod()
    {
        return $this->_oMethod;
    }

    public function acl()
    {
        return $this->_oMethod->getAcl();
    }

}
