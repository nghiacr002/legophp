<?php

namespace APP\Engine;

class AppException extends \Exception
{

    protected $_sHashCode = null;

    public function __construct($sMessage = null, $sCode = null, $mPrevious = null)
    {
        parent::__construct($sMessage, $sCode, $mPrevious);
        $this->_sHashCode = $this->hashCode();
    }

    public function hashCode()
    {
        if (!$this->_sHashCode)
        {
            $this->_sHashCode = substr(md5(time() . uniqid()), 0, 5);
        }
        return $this->_sHashCode;
    }

}
