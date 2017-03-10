<?php

namespace APP\Engine;

use APP\Library\FlexRouter;

class Router
{

    private $_oInstance = null;
    private $_aConfigs = array();
    private $_oUrl = null;

    public function __construct()
    {
        $this->_aConfigs = array();
        $this->init();
        $this->_oUrl = new Url();
    }
	/**
	 * Return instance of url engine
	 * @return \APP\Engine\Url
	 */
    public function url()
    {
        return $this->_oUrl;
    }
	/**
	 * Initialize default values
	 * Running when application is initialized
	 * @return \APP\Library\FlexRouter
	 */
    public function init()
    {
        if (!$this->_oInstance)
        {
            $this->_oInstance = new FlexRouter();
            $this->registerRouter();
        }
        return $this->_oInstance;
    }
	/**
	 * Register all of defined routers of new module
	 * @param string $sModule
	 */
    public function registerRouter($sModule = "default")
    {

        if ($sModule == "default")
        {
            $sPath = APP_PATH_SETTING . 'Router.php';
        }
        else
        {
            $sPath = APP_MODULE_PATH . $sModule . APP_DS . "Router.php";
        }

        if (file_exists($sPath))
        {
            include_once $sPath;
            if (isset($_ROUTER['default']))
            {
                foreach ($_ROUTER as $iKey => $aRouters)
                {
                    if (!isset($this->_aConfigs[$iKey]))
                    {
                        $this->_aConfigs[$iKey] = array();
                    }
                    $this->_aConfigs[$iKey] = array_merge($this->_aConfigs[$iKey], $aRouters);
                    //$this->_aConfigs = array_merge($this->_aConfigs,$_ROUTER['default']);
                }

                foreach ($_ROUTER['default'] as $sName => $aRouter)
                {
                    $method = isset($aRouter['method']) ? $aRouter['method'] : "GET";
                    $route = isset($aRouter['route']) ? $aRouter['route'] : "/";
                    $target = isset($aRouter['target']) ? $aRouter['target'] : null;
                    $this->_oInstance->map($method, $route, $target, $sName);
                }
            }
        }
    }
    /**
     * Return the router defination
     * @param String $sName
     * @param string $sVersion
     * @return NULL|mixed
     */
    public function getRouter($sName, $sVersion = "default")
    {
        return isset($this->_aConfigs[$sVersion][$sName]) ? $this->_aConfigs[$sVersion][$sName] : null;
    }
	/**
	 * return instance of router;
	 * @return \APP\Library\FlexRouter
	 */
    public function instance()
    {
        return $this->_oInstance;
    }
	/**
	 * Magic function to call unknown/hidden function
	 * @param unknown $name
	 * @param array $arguments
	 * @return mixed|NULL
	 */
    public function __call($name, $arguments = array())
    {
        if (method_exists($this->_oInstance, $name))
        {
            $result = call_user_func_array(array($this->_oInstance, $name), $arguments);
            return $result;
        }
        return null;
    }
    /**
     * Forward request to other controller
     * @param array $aRouter
     * Example: array('module' => 'page',
				'controller' => 'index',
				'action' => 'view')
     * @param array $aParams
     * @throws AppException
     * @return boolean
     */
    public function forward($aRouter, $aParams = array())
    {
    	$app = Application::getInstance();
		$oModule = $app->module;
		$sModule = isset($aRouter['module']) ? $aRouter['module'] : "";
		$sController = isset($aRouter['controller']) ? $aRouter['controller'] : "";
		$sAction = isset($aRouter['action']) ? ucfirst($aRouter['action']) : "";
		if(empty($sModule) || empty($sController) || empty($sAction))
		{
			return false;
		}
		if (! $oModule->checkActive ( $sModule )) {
			throw new AppException ( "ACTION NOT FOUND", HTTP_CODE_NOT_FOUND );
		}
		if(isset($aParams['admincp']) && $aParams['admincp'] == true)
		{
			$sController = "Admin" . ucfirst ( $sController );
		}
		$oController = \APP\Engine\Module\Component::factory ( $sModule, $sController, "Controller" );
		$sActionName = ucfirst ( $sAction ) . "Action";
		$oModule->set ( $sModule, $sController, $sAction );
		$app->request->setParams($aParams);
		$oController->{$sActionName} ();
		$oModule->setInstanceController($oController);
    }

}
