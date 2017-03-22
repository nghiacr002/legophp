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
	public function getInfo()
	{
		$app = \APP\Engine\Application::getInstance();
		$aCache = $app->getConfig('cache');
		$sAdapter = ucfirst($aCache['storage']);
		$aOptions = $app->getConfig($aCache['storage']);
		return array(
			'adapter' => $sAdapter,
			'options' => $aOptions
		);
	}
    public function init()
    {

		$aConfigInfo = $this->getInfo();
        $sNamespace = "\\APP\\Library\\Cache\\Storage\\";
        $sAdapter = $aConfigInfo['adapter'];
        $aOptions = $aConfigInfo['options'];
        $sFullClass = $sNamespace . $sAdapter;
        $this->_oStorage = new $sFullClass($aOptions);
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
