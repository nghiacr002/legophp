<?php

namespace APP\Engine;

use APP\Application\Module\User\Model\Auth as UserAuth;
use APP\Application\Module\Core\Model\Setting;
use APP\Application\Module\Core\Model\Language;
use APP\Engine\Module\Plugin;

class Application {
	private $_aConfigs;
	private static $request_verison = null;
	private $_sName = "VN Bean";
	private $_sBrandName = "LegoPHP";
	private $_sVersion = "1.0";
	private static $instance;
	private $_bIsAdminPanel = null;
	private $_aSettings;
	private $_bIsAjaxCall = false;
	private $_bIsRunningUnitTest = null;
	private $_aSharedData = array ();
	/* * *For development highlight and suggestion code, it should be enable** */
	public $request;
	public $database;
	public $auth;
	public $template;
	public $module;
	public $session;
	public $router;
	public $language;
	public $flash;
	public $cookie;
	public $event;
	public $mailer;
	/* * *** */
	public function __construct($aConfigs = null) {
		self::$instance = $this;
		$this->setConfigs ( $aConfigs );
		if ($this->isDebug ()) {
			$this->enableDebug ();
		}
		if(isset($aConfigs['apps']))
		{
			$this->_sVersion = $aConfigs['apps']['version'];
			$this->_sName = $aConfigs['apps']['name'];
		}
	}
	public function setSharedData($sKey, $mValue) {
		$this->_aSharedData [$sKey] = $mValue;
		return $this;
	}
	public function getSharedParam($sKey) {
		return isset ( $this->_aSharedData [$sKey] ) ? $this->_aSharedData [$sKey] : null;
	}
	public function getBrandName() {
		return $this->_sBrandName;
	}
	public function getBrandNameUrl() {
		return "http://legophp.com";
	}
	public function getSystemInformation() {
		$aDatabaseInfo = $this->database->getInfo ();
		return array (
				'sName' => $this->_sName,
				'sVersion' => $this->_sVersion,
				'sPHPVersion' => PHP_VERSION,
				'aDatabaseInfo' => array (
						'connector' => $this->getConfig ( 'db', 'adapter' ),
						'version' => $aDatabaseInfo ['server_info']
				)
		);
	}
	public function isRunningUnitTest() {
		if (! $this->_bIsRunningUnitTest) {
			$this->_bIsRunningUnitTest = isset ( $this->_aConfigs ['unit_test'] ) ? $this->_aConfigs ['unit_test'] : false;
		}
		return $this->_bIsRunningUnitTest;
	}
	public function getTemplate($sTemplateName = "") {
		if ($this->template->isInLegoMode ()) {
			$sTemplateName = "Widget.tpl";
		} elseif (empty ( $sTemplateName )) {
			$sTemplateName = "Master.tpl";
		}
		$sTemplateName = \APP\Engine\Template::getFileName($sTemplateName);
		return $sTemplateName;
	}
	public function init() {
		Plugin::fetch ( 'init_start' );
		$this->session = new \APP\Engine\Session ();
		$this->cookie = new \APP\Engine\Cookie ();
		$this->database = new \APP\Engine\Database ();

		$this->_aSettings = (new Setting ())->getAll ();
		$aLanguage = (new Language ())->getDefaultLanguage ();
		$sDefaultLanguage = isset ( $aLanguage->language_code ) ? $aLanguage->language_code : $this->_aConfigs ['system'] ['language'];
		$this->language = new \APP\Engine\Language ( $sDefaultLanguage );

		$this->flash = new \APP\Engine\Flash ();
		$this->request = new \APP\Engine\Request ();
		$this->router = new \APP\Engine\Router ();
		$this->mailer = new \APP\Engine\Mailer();
		$iIndexSeg = 0;
		if (! empty ( $this->_aConfigs ['system'] ['base_path'] ) &&  $this->_aConfigs ['system'] ['base_path'] !='/') {
			$iIndexSeg = 1;
		}
		if ($this->request->seg ( $iIndexSeg ) == $this->getConfig ( 'system', 'admin_path' )) {
			$this->_bIsAdminPanel = true;
		}
		$this->event = new Event ();
		$this->template = new Template ();
		$this->module = new \APP\Engine\Module ();

		$this->auth = new \APP\Engine\Auth ( new UserAuth () );
		$this->module->initialize ();

		if ($this->request->get ( 'bIsAjax' ) == 1 || (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && $_SERVER ['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest")) {
			$this->_bIsAjaxCall = true;
		}
		@eval(Plugin::fetch ( 'init_end',true ));
	}
	public function isAjaxCall() {
		return $this->_bIsAjaxCall;
	}
	public function getName() {
		return $this->_sName;
	}
	public function getVersion() {
		return $this->_sVersion;
	}
	public function isAdminPanel() {
		return $this->_bIsAdminPanel;
	}
	public static function getInstance() {
		return self::$instance;
	}
	public function setConfigs($aConfigs = array()) {
		$this->_aConfigs = $aConfigs;
		return $this;
	}
	public function getConfigs() {
		return $this->_aConfigs;
	}
	public function getConfig($sName, $mIndex = null) {
		$mValues = isset ( $this->_aConfigs [$sName] ) ? $this->_aConfigs [$sName] : null;
		if ($mValues) {
			if ($mIndex) {
				return isset ( $mValues [$mIndex] ) ? $mValues [$mIndex] : null;
			}
			return $mValues;
		}
		return null;
	}
	public function getSetting($sName, $mDefaultValue = null) {
		if (isset ( $this->_aSettings [$sName] )) {
			if ($this->_aSettings [$sName] ['real_value'] === NULL) {
				return $this->_aSettings [$sName] ['default_value'];
			}
			return $this->_aSettings [$sName] ['real_value'];
		}
		return $mDefaultValue;
	}
	public function setConfig($sName, $mIndex = null, $mValue)
	{
		if($mIndex)
		{
			$this->_aConfigs[$sName][$mIndex] = $mValue;
		}
		else
		{
			$this->_aConfigs[$sName] = $mValue;
		}
		return $this;
	}
	public function setSetting($sName, $mValue) {
		$this->_aSettings [$sName] ['real_value'] = $mValue;
		return $this;
	}
	public function getBaseURL() {
		$sProtocol = "http://";
		if (isset ( $_SERVER ['HTTPS'] )) {
			$sProtocol = "https://";
		}
		$sDomain = isset ( $_SERVER ['SERVER_NAME'] ) ? $_SERVER ['SERVER_NAME'] : "";
		$sPort = isset ( $_SERVER ['SERVER_PORT'] ) ? $_SERVER ['SERVER_PORT'] : "";

		if ($sPort != 80 && $sPort != 443) {
			$sDomain = $sDomain . ":" . $sPort;
		}
		return $sProtocol . $sDomain . $this->getConfig ( 'system', 'base_path' );
	}
	public function enableDebug($bEnable = true) {
		Debug::register ( $bEnable );
		Debug::$app_start_time = microtime ( true );
		return $this;
	}
	public function isDebug() {
		if (isset ( $this->_aConfigs ['enviroment'] ) && $this->_aConfigs ['enviroment'] == "development") {
			return true;
		}
		return false;
	}
	public function execute() {
		$this->init ();
		$sBasePath = $this->_aConfigs ['system'] ['base_path'];
		if ($this->isAdminPanel ()) {
			if ($sBasePath == "" || $sBasePath == "/") {
				$sBasePath = "/";
			}
			$sBasePath = $sBasePath . $this->_aConfigs ['system'] ['admin_path'] . "/";
		}

		if (! empty ( $sBasePath )) {
			$this->router->instance ()->setBasePath ( $sBasePath );
		}
		$aMatch = $this->router->match ();
		@eval(Plugin::fetch ( 'router_detect',true ));
		if (isset ( $aMatch ['name'] )) {
			if ($aMatch ['name'] == "app_parsing_default") {
				$aRouter = $aMatch;
			} else {
				$aRouter = $this->router->getRouter ( $aMatch ['name'] );
			}

			$this->module->setRouter ( $aRouter );
			if ($this->isAdminPanel ()) {
				$this->checkLogin ( true );
				if (! $this->auth->acl ()->hasPerm ( 'core.can_access_admincp' )) {
					$this->router->url ()->redirect ( 'user/subscribe', array (), 'You do not have permission to access', 'error' );
				}
			}

			if (isset ( $aRouter ['route'] )) {
				$aMatch ['params'] ['router'] = $aRouter ['route'];
				$this->request->setParams ( $aMatch ['params'] );

				if (is_callable ( $aMatch ['target'] )) {
					call_user_func_array ( $aMatch ['target'], $aMatch ['params'] );
					exit ();
				} else {
					$sModule = isset ( $aRouter ['module'] ) ? $aRouter ['module'] : "";

					if (! $this->module->checkActive ( $sModule )) {
						throw new AppException ( "ACTION NOT FOUND", HTTP_CODE_NOT_FOUND );
					}
					$sController = isset ( $aRouter ['controller'] ) ? $aRouter ['controller'] : "";
					if ($this->isAdminPanel ()) {
						$sController = "Admin" . ucfirst ( $sController );
					}
					$sAction = isset ( $aRouter ['action'] ) ? $aRouter ['action'] : "Notfound";

					$oController = \APP\Engine\Module\Component::factory ( $sModule, $sController, "Controller" );

					if (! $oController) {
						$sAction = isset ( $aRouter ['controller'] ) ? $aRouter ['controller'] : "";
						$sAction = ucfirst ( $sAction );
						if ($this->isAdminPanel ()) {
							$sController = "AdminIndex";
						} else {
							$sController = "Index";
						}
						$oController = \APP\Engine\Module\Component::factory ( $sModule, $sController, "Controller" );
					}
					$sController = ucfirst ( $sController );
					$sActionName = ucfirst ( $sAction ) . "Action";
					if ($oController && method_exists ( $oController, $sActionName )) {

						if ($this->request->getHeader ( 'router-detect' ) == true) {
							system_display_result ( array (
									'module' => $sModule,
									'controller' => $sController,
									'action' => $sAction
							) );
						}
						$this->module->set ( $sModule, $sController, $sAction );
						$this->module->setInstanceController($oController);
						$oController->{$sActionName} ();
						$this->render();
						$this->clean();
					} else {
						throw new AppException ( "ACTION[" . $sModule . '.' . $sController . '.' . $sAction . "] NOT FOUND", HTTP_CODE_NOT_FOUND );
					}
				}
			} else {
				throw new AppException ( "ACTION[" . $sModule . '.' . $sController . '.' . $sAction . "] NOT FOUND", HTTP_CODE_NOT_FOUND );
			}
		} else {
			throw new AppException ( "UNKNOW ROUTER", HTTP_CODE_NOT_FOUND );
		}
	}
	public function render()
	{
		$this->template->detectLayout ();
		$oController = $this->module->getInstanceController();
		$sAction = $this->module->getCurrentAction();
		$sContent = $oController->getContent ( $sAction );
		return $this->_render($sContent);
	}
	protected function _render($sContent) {
		// check if has layout extends for body view
		if ($this->template->isInLegoMode ()) {
			$sSiteContent = $this->template->getLayout ()->buildContent ( $sContent );
			$sContent = $this->template->assign ( array (
					'site_content' => $sSiteContent
			) )->render ();
		}
		system_display_result ( $sContent );
	}
	public function checkLogin($bRedirect = false) {
		$bIsLogin = $this->auth->isAuthenticated ();
		if (! $bIsLogin && $bRedirect) {
			$sUrl = $this->router->url ()->getCurrentUrl ();
			$this->router->url ()->redirect ( 'user/login', array (
					'url' => $sUrl
			) );
			exit ();
		}
		return $bIsLogin;
	}
	public function clean() {
		$this->flash->clear ( true );
	}
	public function __get($sName) {
		if (isset ( $this->{$sName} )) {
			return $this->{$sName};
		}
		$sClassName = ucfirst ( $sName );
		$sClassName = "APP\\Engine\\" . $sClassName;
		$this->{$sName} = new $sClassName ();
		return $this->{$sName};
	}
}
