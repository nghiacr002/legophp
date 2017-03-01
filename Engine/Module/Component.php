<?php

namespace APP\Engine\Module;

use APP\Engine\Module\View;
use APP\Engine\Object as Object;

class Component extends Object
{
	protected $_sModule = "";
	protected $_sName = "";
	public $helper = null;
	public $view = null;
	public function __construct()
	{
		$this->preInitialize ();
		parent::__construct ();
		$this->view = new View ();
		$this->helper = new Helper ();
		$this->afterInitialize ();
	}
	public function getId()
	{
		return $this->_sModule . '-' . $this->_sName;
	}
	public function setModule($sModule)
	{
		$this->_sModule = $sModule;
		return $this;
	}
	public function setName($sName)
	{
		$this->_sName = $sName;
		return $this;
	}
	protected function preInitialize()
	{
		return true;
	}
	protected function afterInitialize()
	{
		return true;
	}
	public function template()
	{
		return $this->app->template;
	}
	public function language()
	{
		return $this->app->language;
	}
	public function flash()
	{
		return $this->app->flash;
	}
	public function url()
	{
		return $this->app->router->url ();
	}
	public function auth()
	{
		return $this->app->auth;
	}
	public function router()
	{
		return $this->app->router;
	}
	public function setParams($aParams = array())
	{
		if (! $this->_aData)
		{
			$this->_aData = array ();
		}
		if (! is_array ( $aParams ))
		{
			$aParams = array (
					$aParams
			);
		}
		$this->_aData = array_merge ( $this->_aData, $aParams );
		return $this;
	}
	public function getParams()
	{
		return $this->_aData;
	}
	public static function factory($sModule, $sName, $sType = "Controller")
	{
		$sModuleComponentPath = APP_MODULE_PATH . ucfirst ( $sModule ) . APP_DS . $sType . APP_DS . ucfirst ( $sName ) . ".php";
		if (file_exists ( $sModuleComponentPath ))
		{
			include_once $sModuleComponentPath;
			$sClassName = ucfirst ( $sName ) . $sType;
			$sNamespace = "\\APP\\Application\\Module\\" . ucfirst ( $sModule ) . "\\";
			$sClassName = $sNamespace . $sClassName;
			$oObject = new $sClassName ();
			if (method_exists ( $oObject, 'setModule' ))
			{
				$oObject->setName ( ucfirst ( $sName ) );
				$oObject->setModule ( ucfirst ( $sModule ) );
			}
			return $oObject;
		}
		return null;
	}
}
