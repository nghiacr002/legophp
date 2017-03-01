<?php

namespace APP\Engine;

class Object
{

    protected $_aData;
    protected $app = null;

    public function __construct()
    {
        $this->app = $this->app();
        $this->_aData = array();
    }

    public function __set($sName, $mValue)
    {
        $this->_aData[$sName] = $mValue;
        return $this;
    }

    public function app()
    {
        return \APP\Engine\Application::getInstance();
    }

    public function __isset($sName)
    {
        return isset($this->_aData[$sName]);
    }

    public function __get($sName)
    {
        if ($sName == "app")
        {
            return $this->app();
        }
        if (array_key_exists($sName, $this->_aData))
        {
            return $this->_aData[$sName];
        }
        return null;
    }

    public function __call($sName, $arguments = array())
    {
        if (method_exists($this, $sName))
        {
            call_user_func_array(array($this, $sName), $arguments);
        }
        return null;
    }

    public static function __set_state($array)
    {
        $obj = new static();
        foreach ($array as $key => $value)
        {
            $obj->{$key} = $value;
        }
        return $obj;
    }

    public function getProps()
    {
        return $this->_aData;
    }

    public function toArray()
    {
        return $this->_aData;
    }
    public function toString()
    {
    	return get_class($this);
    }
}
