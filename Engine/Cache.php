<?php

namespace APP\Engine;

class Cache
{

    protected $_oStorage;
    protected static $instance;

    public function __construct()
    {
        $this->init();
        self::$instance = $this;
    }

    public function init()
    {
        $this->_aConfigs = \APP\Engine\Application::getInstance()->getConfig('cache');
        $sNamespace = "\\APP\\Library\\Cache\\Storage\\";
        $sAdapter = ucfirst($this->_aConfigs['storage']);
        $sFullClass = $sNamespace . $sAdapter;
        $this->_oStorage = new $sFullClass();
        return $this;
    }

    public static function getInstance()
    {
        if (!self::$instance)
        {
            $tmp = new Cache();
            self::$instance = $tmp;
        }
        return self::$instance;
    }

    public function getStorage()
    {
        return $this->_oStorage;
    }

    public function getCaches()
    {
        return $this->_oStorage->getCaches();
    }

}
