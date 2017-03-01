<?php

namespace APP\Engine;

class Cookie
{

    private $_sPrefix = "";
    private $_iExpiredTime = 30;
    private $_sPath = "";
    private $_sDomain = "";

    public function __construct()
    {
        $app = Application::getInstance();
        $this->_sPrefix = $app->getConfig('cookie', 'prefix') . '_' . md5($app->getConfig('security', 'random'));
        $this->_iExpiredTime = $app->getConfig('cookie', 'expried');
        $this->_sPath = $app->getConfig('cookie', 'path');
        $this->_sDomain = $app->getConfig('cookie', 'domain');
    }

    /**
     * Set Cookie value
     * 
     * @param mixed $sName
     * @param mixed $aValue
     */
    public function set($sName, $aValue, $iExpired = null)
    {
        if ($iExpired == null)
        {
            $iExpired = $this->_iExpiredTime;
        }
        $iExpired = APP_TIME + (60 * 60 * 24 * $iExpired);
        setcookie($this->_sPrefix . '[' . $sName . ']', $aValue, $iExpired, $this->_sPath, $this->_sDomain);
        $_COOKIE[$this->_sPrefix . '[' . $sName . ']'] = $aValue;
        return $this;
    }

    public function remove($sName)
    {
        if (isset($_COOKIE[$this->_sPrefix][$sName]))
        {
            $iExpired = APP_TIME - 3600;
            setcookie($this->_sPrefix . '[' . $sName . ']', $aValue, $iExpired, $this->_sPath, $this->_sDomain);
        }
        return $this;
    }

    public function get($sName, $bGlobal = false)
    {
        if ($bGlobal == true)
        {
            if (isset($_COOKIE[$sName]))
            {
                return $_COOKIE [$sName];
            }
        } else
        {
            if (isset($_COOKIE [$this->_sPrefix] [$sName]))
            {
                return $_COOKIE [$this->_sPrefix] [$sName];
            }
        }
        return null;
    }

}
?>

