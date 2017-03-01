<?php

namespace APP\Engine;

class Session
{

    private $_sPrefix = "";

    public function __construct()
    {
        $this->_sPrefix = \APP\Engine\Application::getInstance()->getConfig('system', 'session_prefix');
        if (!isset($_SESSION[$this->_sPrefix]))
        {
            $_SESSION[$this->_sPrefix] = array();
        }
    }

    /**
     * Set value to session param.
     * 
     * @param mixed $sName
     * @param mixed $aValue
     */
    public function set($sName, $aValue)
    {
        $_SESSION[$this->_sPrefix][$sName] = $aValue;
        return $this;
    }

    /**
     * Remove value by name
     * 
     * @param mixed $sName
     */
    public function remove($sName = "")
    {
        if (empty($sName))
        {
            $_SESSION[$this->_sPrefix] = array();
        }
        if (isset($_SESSION[$this->_sPrefix][$sName]))
        {
            unset($_SESSION[$this->_sPrefix][$sName]);
        }
        return $this;
    }

    /**
     * Get value from session by name.
     * 
     * @param mixed $sName
     * @return mixed
     */
    public function get($sName)
    {
        if (isset($_SESSION[$this->_sPrefix][$sName]))
        {
            return $_SESSION[$this->_sPrefix][$sName];
        }
        return null;
    }

    /**
     * Return current session id
     * 
     */
    public function getSessionId()
    {
        return session_id();
    }
	public function cleanByPrefix($sPrefix)
	{
		if (isset($_SESSION[$this->_sPrefix]) && count($_SESSION[$this->_sPrefix]))
		{
			foreach($_SESSION[$this->_sPrefix] as $sKey => $mValue)
			{
				if(strpos($sKey, $sPrefix) === 0)
				{
					unset($_SESSION[$this->_sPrefix][$sKey]);
				}
			}
		}
	}
    public function test()
    {
        echo "test";
    }

}
?>

