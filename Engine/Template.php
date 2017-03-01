<?php

namespace APP\Engine;

use APP\Library\App_Twig_Loader_Filesystem;
use APP\Application\Module\Theme\Model\Theme;
use APP\Application\Module\Theme\Model\ModuleController;

class Template extends Object
{

    protected $_sTemplateFile = "";
    protected $_oTwig;
    protected $_sTemplate = "default";
    protected $_sTitle = "";
    protected $_sKeyword;
    protected $_sDescription;
    protected $_aHeader = array();
    protected $_aBreadCrumb = array();
   	protected $_aCoreJS = array();
   	protected $_oTheme = null;
   	protected $_oLayout = null;
   	protected $_bIsInLegoMode = false;
   	protected $_aIncludeFiles = array();
    public static $instance;

    public function __construct()
    {
        parent::__construct();
        $this->_aData = array();
        self::$instance = $this;
        $loader = new App_Twig_Loader_Filesystem();
        $sTemplatePath = "";
        if (! $this->app ()->isAdminPanel ())
        {
        	$this->_oTheme = (new Theme())->getDefaultTheme();
        	if(!$this->_oTheme)
        	{
        		$this->_sTemplate = $this->app->getConfig ( 'template', 'frontend' );
        	}
        	else
        	{
        		$this->_sTemplate = $this->_oTheme->folder;
        	}
			$sTemplatePath = APP_THEME_PATH . "Frontend" . APP_DS . $this->_sTemplate . APP_DS;
		}
		else
		{
			$this->_oTheme = (new Theme())->getDefaultTheme("backend");
			if(!$this->_oTheme)
			{
				$this->_sTemplate = $this->app->getConfig ( 'template', 'backend' );
			}
			else
			{
				$this->_sTemplate = $this->_oTheme->folder;
			}
			$sTemplatePath = APP_THEME_PATH . "Backend" . APP_DS . $this->_sTemplate . APP_DS;
		}
		$sLayoutPath = $sTemplatePath . "Layout" . APP_DS;
        $loader->setPaths(array(
        	$sLayoutPath,
        	//APP_THEME_PATH. "PageLayout". APP_DS
        ));
        $sDevelopmentMode = $this->app->getConfig('enviroment');
        $this->_oTwig = new \Twig_Environment($loader, array(
            'cache' => APP_CACHE_PATH . 'Theme' . APP_DS,
            'debug' => ($sDevelopmentMode == "development") ? true : false
        ));
        $sIncludeHeader = $sTemplatePath . "Function" . APP_DS . "Header.php";
        if (file_exists($sIncludeHeader))
        {
            include_once $sIncludeHeader;
        }
    }
    public function inLegoMode($mValue = true)
    {
    	$this->_bIsInLegoMode = $mValue;
    	return $this;
    }
    public function isInLegoMode()
    {
    	return $this->_bIsInLegoMode;
    }
    public function getLayout()
    {
    	return $this->_oLayout;
    }
    public function setLayout($mValue)
    {
    	$this->_oLayout = $mValue;
    	return $this;
    }
	public function getCurrentTheme()
	{
		return $this->_oTheme;
	}
    public function getEngine()
    {
        return $this->_oTwig;
    }

    public function addExtension($mExtension)
    {
        $this->_oTwig->addExtension($mExtension);
        return $this;
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    public function getTemplateName()
    {
        return $this->_sTemplate;
    }

    public function prependPath($sPath)
    {
        $this->_oTwig->getLoader()->prependPath($sPath);
    }

    public function setPath($sPath)
    {
        $this->_oTwig->getLoader()->setPaths($sPath);
        return $this;
    }

    public function assign($aParams = array())
    {
        if (!is_array($this->_aData))
        {
            $this->_aData = array();
        }
        if (!is_array($aParams))
        {
            $aParams = array($aParams);
        }
        $this->_aData = array_merge($this->_aData, $aParams);
        return $this;
    }

    public function setParams($aParams)
    {
        $this->_aData = $aParams;
        return $this;
    }

    public function getBaseURL()
    {
        if ($this->app->isAdminPanel())
        {
            return $this->app->getBaseURL() . 'Application/Theme/Backend/' . $this->app->getConfig('template', 'backend') . '/';
        }
        return $this->app->getBaseURL() . 'Application/Theme/Frontend/' . $this->app->getConfig('template', 'frontend') . '/';
    }

    public function render($sName = "", $bReturn = true)
    {
        if (empty($sName))
        {
            $sName = "Master";
        }
        $sName = self::getFileName($sName);
        $this->_aData['flash'] = $this->app->flash;
        $this->_aData['aBreadCrumb'] = $this->_aBreadCrumb;
        $mData = $this->_oTwig->render($sName, $this->_aData);
        if ($bReturn)
        {
            return $mData;
        }
        echo $mData;
    }

    public function getUrl($sURI = '', $aParams = array())
    {
        return $this->app->router->url()->makeUrl($sURI, $aParams);
    }

    public function getCSS()
    {
        $sContent = "";
        $aIncludedCSS = isset($this->_aHeader['Css']) ? $this->_aHeader['Css'] : array();
        if (count($aIncludedCSS))
        {
            if (!$this->app->getConfig('minify', 'css'))
            {
                foreach ($aIncludedCSS as $sFileCss => $sFileName)
                {
                    $sContent .= '<link href="' . $sFileCss . '" rel="stylesheet" type="text/css">' . "\n";
                }
            }
            else
            {
                $sCacheId = 'css' . md5(base64_encode(serialize($aIncludedCSS)));
                $oCache = (new \APP\Engine\Cache())->getStorage();
                $oMinify = new \APP\Engine\Minify();
                $sBaseURL = Application::getInstance()->getBaseURL();
                $aFiles = $oCache->get($sCacheId);
                if (!count($aFiles) || $aFiles == false || defined('APP_DEV_CHECK_CSS'))
                {
                    $aFiles = $oMinify->minCSS($aIncludedCSS, $sCacheId);
                    $oCache->set($sCacheId, $aFiles);
                }
                if (count($aFiles))
                {
                    if ($this->app->getConfig('gzip', 'css'))
                    {
                        $sListFile = "";
                        foreach ($aFiles as $iKey => $sFile)
                        {
                            $sListFile .= "," . $iKey;
                        }
                        $sListFile = substr($sListFile, 1);
                        $sContent = '<link href="' . $sBaseURL . 'gzip.php?t=css&f=' . $sListFile . '" rel="stylesheet" type="text/css">' . "\n";
                    } else
                    {
                        foreach ($aFiles as $iKey => $sFile)
                        {
                            $sContent .= '<link href="' . $sFile . '" rel="stylesheet" type="text/css">' . "\n";
                        }
                    }
                }
            }
        }
        return $sContent;
    }

    public function getJS()
    {
    	$this->_buildJSFile($this->_aCoreJS);
        $sContent = "";
        $aIncludedJs = isset($this->_aHeader['Js']) ? $this->_aHeader['Js'] : array();

        if (count($aIncludedJs))
        {
            if (!$this->app->getConfig('minify', 'js'))
            {
                foreach ($aIncludedJs as $sFileJS => $sFileName)
                {
                    $sContent .= '<script src="' . $sFileJS . '"></script>' . "\n";
                }
            } else
            {
                $sCacheId = 'js' . md5(base64_encode(serialize($aIncludedJs)));
                $oCache = (new \APP\Engine\Cache())->getStorage();
                $oMinify = new \APP\Engine\Minify();
                $sBaseURL = Application::getInstance()->getBaseURL();
                $aFiles = $oCache->get($sCacheId);
                if (!count($aFiles) || $aFiles == false || defined('APP_DEV_CHECK_JS'))
                {
                    $aFiles = $oMinify->minJS($aIncludedJs, $sCacheId);
                    $oCache->set($sCacheId, $aFiles);
                }
                if (count($aFiles))
                {
                    if ($this->app->getConfig('gzip', 'js'))
                    {
                        $sListFile = "";
                        foreach ($aFiles as $iKey => $sFile)
                        {
                            $sListFile .= "," . $iKey;
                        }
                        $sListFile = substr($sListFile, 1);
                        $sContent = '<script src="' . Application::getInstance()->getBaseURL() . 'gzip.php?t=js&f=' . $sListFile . '"></script>' . "\n";
                    } else
                    {
                        foreach ($aFiles as $iKey => $sFile)
                        {
                            $sContent .= '<script src="' . $sFile . '"></script>' . "\n";
                        }
                    }
                }
            }
        }
        return $sContent;
    }

	public function getInclude($sFile, $sPath)
	{
		list($sType, $sHeaderKey, $sURL ) = $this->_getFileURL($sFile, $sPath);
		if(!file_exists($sURL))
		{
			return "";
		}
		if(is_array($this->_aIncludeFiles) && count($this->_aIncludeFiles) && in_array($this->_aIncludeFiles, $sURL))
		{
			return "";
		}
		$this->_aIncludeFiles[] = $sURL;
		switch ($sType)
		{
			case 'Js':
				return '<script src="' . $sHeaderKey . '"></script>';
			case 'Css':
				return '<link href="' . $sHeaderKey . '" rel="stylesheet" type="text/css">';
		}
		return "";
	}
    public function getMeta()
    {
        $aMeta = isset($this->_aHeader['Meta']) ? $this->_aHeader['Meta'] : array();
        return implode(PHP_EOL, $aMeta);
    }
	public function setMeta($sValue)
	{
		$this->_aHeader['Meta'][] = $sValue;
		return $this;
	}
    public function getTitle()
    {
        $sTitle = "";
        if (empty($this->_sTitle))
        {
            $sTitle = $this->app->getSetting('core.site_name');
        }
        else
        {
            $sTitle = $this->app->getSetting('core.site_name')
                    . " " . $this->app->getSetting('core.site_delimiter_title') . " " . $this->_sTitle;
        }
        return $sTitle;
    }

    public function getDescription()
    {
        return $this->_sDescription;
    }

    public function getKeywords()
    {
        return $this->_sKeyword;
    }

    public function setTitle($sTitle)
    {
        $this->_sTitle = $sTitle;
        return $this;
    }
	public function appendTitle($sTitle)
	{
		if(!empty($this->_sTitle))
		{
			$this->_sTitle.= " ".$this->app->getSetting('core.site_delimiter_title');
		}
		$this->_sTitle = $this->_sTitle . " " . $sTitle;
		return $this;
	}
    public function setDescription($sDescription)
    {
        $this->_sDescription = $sDescription;
        return $this;
    }

    public function setKeyword($sKeyword)
    {
        $sKeyword = Parse::getKeywords($sKeyword);
        $this->_sKeyword = $sKeyword;
        return $this;
    }

    public function setHeader($aHeader = array())
    {
        if (!is_array($aHeader))
        {
            $aHeader = array($aHeader);
        }

        foreach ($aHeader as $iKey => $sPathFile)
        {

        	list($sType, $sKeyHeader, $sFile) = $this->_getFileURL($iKey,$sPathFile);
			$this->_aHeader[$sType][$sKeyHeader] = $sFile;
        }
        return $this;
    }
	protected function _getFileURL($iKey,$sPathFile)
	{
		$aParts = explode('_', $sPathFile);
		$sType = "Meta";
		if (strpos($iKey, ".js") !== false)
		{
			$sType = "Js";
		}
		if (strpos($iKey, ".css") !== false)
		{
			$sType = "Css";
		}
		if ($sType != "Meta")
		{
			$sPath = $this->app->isAdminPanel() ? "Backend" : "Frontend";
			$sBaseURL = $this->app->getBaseURL();
			$sPart1 = ucfirst($aParts[1]);
			$aParts[0] = strtolower($aParts[0]);
			switch ($aParts[0])
			{
				case 'public':
					$sUrlPath = $sBaseURL . 'Public/' . $sPart1 . '/';
					$sFolderStaticPath = APP_ROOT_PATH . 'Public' . APP_DS . $sPart1 . APP_DS;
					break;
				case 'theme':
					$sUrlPath = $sBaseURL . 'Application/Theme/' . $sPath . '/' . $sPart1 . '/' . $sType . '/';
					$sFolderStaticPath = APP_THEME_PATH . $sPath . APP_DS . $sPart1 . APP_DS . $sType . APP_DS;
					break;
				default:
					$sUrlPath = $sBaseURL . 'Application/Module/' . $sPart1 . '/Public/' . $sType . '/';
					$sFolderStaticPath = APP_MODULE_PATH . $sPart1 . APP_DS . 'Public' . APP_DS . $sType . APP_DS;
					break;
			}
			$sKeyHeader = $sUrlPath . $iKey;
			$sPathFile = $sFolderStaticPath . str_replace('/', APP_DS, $iKey);
			;
		} else
		{
			$sKeyHeader = "";
			//$this->_aHeader[$sType][] = $sPathFile;
		}
		return array($sType, $sKeyHeader, $sPathFile);
	}
    public static function getFileName($sName)
    {
        $sPrefix = \APP\Engine\Application::getInstance()->getConfig('template', 'prefix');
        if (strpos($sName, $sPrefix) === false)
        {
            $sName = $sName . $sPrefix;
        }
        return $sName;
    }

    public function setBreadCrumb($aBreadCrumb = array())
    {
        $this->_aBreadCrumb = $aBreadCrumb;
        return $this;
    }
    public function setJsParams($aParams = array())
    {
    	return $this->_setCoreJsData("params", $aParams);
    }
    public function setJsPhrase($aPhrases = array())
    {
       	return $this->_setCoreJsData("pharses", $aPhrases);
    }
    public function getLogo()
    {
    	$sLogoPath = "";
    	if($this->_oTheme && $this->_oTheme->theme_id)
    	{
    		$sLogoPath = $this->_oTheme->getLogoPath();
    	}
    	if($sLogoPath)
    	{
    		$sContent = '<img src="'.$sLogoPath.'" class="logo-header"/>';
    	}
    	else
    	{
    		$sContent = $this->app->getSetting('core.site_name');
    	}
    	return $sContent;
    }
    public function detectLayout($sRouterName = "")
    {
    	if(empty($sRouterName))
    	{
    		$sRouterName = $this->app->module->getCurrentPathRouter();
    	}
    	//detect Module controller Layout, if not, return default
    	$oModuleControllerLayout = (new ModuleController())->getOne($sRouterName,'router_name');
    	if($oModuleControllerLayout && $oModuleControllerLayout->controller_id)
    	{
    		$this->setLayout($oModuleControllerLayout);
    		$this->inLegoMode(true);
    	}
    	return $this;
    }
    private function _setCoreJsData($sType, $mData)
    {
    	foreach($mData as $sKey => $mDataSub)
    	{
    		if(is_array($mDataSub))
    		{
    			foreach($mDataSub as $sKey2 => $sData)
    			{
    				$this->_aCoreJS[$sType][$sKey2] = $sData;
    			}
    		}
    		else
    		{
    			$this->_aCoreJS[$sType][$sKey] = $mDataSub;
    		}
    	}
    	return $this;
    }
	protected function _buildJSFile($aValues = array())
	{
		if(count($aValues))
		{
			$sCacheId = 'params' . md5(json_encode($aValues)).'.js';
			$sContent = "var CORE_CUSTOM = ".json_encode($aValues).";";
			$oFile = new File();
			$sPath = APP_PUBLIC_PATH. "Asset". APP_DS . $sCacheId;
			if(!file_exists($sPath) || defined('APP_NO_CACHE_PHRASE'))
			{
				$oFile->write($sPath,$sContent);
			}
			$this->setHeader(array(
				$sCacheId => 'public_asset',
			));
		}
		return false;
	}
}
