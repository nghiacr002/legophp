<?php

namespace APP\Engine;

use APP\Engine\Application;
use APP\Engine\Module\Exporter;

class Installation
{

    private $_aSteps = array(
        'checkRequirements',
        'installModules',
        'addAdminAccount',
        'completeInstallation',
    );
    private $_sCurrentStep = "checkRequirements";
    private $_aErrors = array();
    private $_sBaseUrl = "";
    public $app = null;

    public function __construct()
    {
        $sCurrentURL = $this->getCurrentUrl();
        $this->_sBaseUrl = str_replace('install.php', '', $sCurrentURL);
    }

    public function getBaseUrl()
    {
        return $this->_sBaseUrl;
    }

    public function start(Application $app)
    {
        $this->app = $app;
        if ($this->checkRequirements())
        {
            echo "Completed";
            exit;
        } else
        {
            $this->installState("INSTALL FAIL. CHECK FOR RED COMMENTS");
        }
    }

    public function installState($sMg)
    {
        $this->_echo("===" . $sMg . "===");
    }
	public function installCustomScript(Application $app)
	{
		$app->database = new Database();
		$app->module = new \APP\Engine\Module();
		$aInstalledModules = $app->module->getInstalledModules ();
		foreach($aInstalledModules as $iKey => $oModule)
		{
			$oInstallation = $oModule->installation ();
			if($oInstallation && method_exists($oInstallation, 'process'))
			{
				$oInstallation->setInstallVersion($app->getVersion());
				try{
					$oInstallation->process();
				}
				catch (AppException $ex)
				{
					$this->addError($ex->getMessage());
				}

			}
		}
		echo "Completed";
		exit;
	}
    public function config(Application $app)
    {
        ob_clean();
        $aDBConfig = isset($_REQUEST['db']) ? $_REQUEST['db'] : array();
        if (empty($aDBConfig['host']))
        {
            $this->addError("Host cannot be empty");
        }
        if (empty($aDBConfig['name']))
        {
            $this->addError("Database Name cannot be empty");
        }
        if (empty($aDBConfig['user']))
        {
            $this->addError("User Name cannot be empty");
        }
        if (empty($aDBConfig['prefix']))
        {
            $this->addError("Prefix cannot be empty");
        }
        if (!$this->hasError())
        {
            $sFile = APP_PATH_SETTING . 'Config.default.php';
            if (!file_exists($sFile))
            {
                $this->addError("Config.default.php not found");
            } else
            {
                $this->_echo("Save configuration to file");
                $aConfigs = $app->getConfigs();
                $aConfigs['db'] = $aDBConfig;
                $sPathDir = dirname($_SERVER["REQUEST_URI"]);
                if(empty($sPathDir))
                {
                	$sPathDir = "/";
                }
                else
                {
                	$sPathDir.="/";
                }
                $aConfigs['system']['base_path'] = $sPathDir;
                $sConfigFile = APP_PATH_SETTING . 'Config.php';
                $sConfigString = '<?php $_CONF = ' . var_export($aConfigs, true) . '?>';
                @file_put_contents($sConfigFile, $sConfigString);
                chmod($sConfigFile, 0644);
                echo "Completed";
                exit;
            }
        }
    }

    public function installDB(Application $app)
    {
        try
        {
        	$oFile = new File();
            $app->database = new Database();
            $sSQLPathFolder = APP_ROOT_PATH. "Install". APP_DS ."Sql". APP_DS;
            $aFiles = $oFile->scanFolder($sSQLPathFolder,false,'.sql');

			if(count($aFiles))
			{
				$sSqlPrefixTable = $app->getConfig('db','prefix');
				$oAdapter = $app->database->getAdapter();
				foreach($aFiles as $iKey => $sFileName)
				{
					$sSQLPath = $sSQLPathFolder . APP_DS . $sFileName;
					$this->_echo("Install SQL ". $sFileName);
					$sContent = $oFile->read($sSQLPath);
					//cat content into SQL Query
					$aSQLQueries = explode(Exporter::SPECTATOR,$sContent);
					if(count($aSQLQueries))
					{
						foreach($aSQLQueries as $iKey => $sSql)
						{
							$sSql = str_replace(Exporter::HASH_PREFIX,$sSqlPrefixTable, $sSql );
							if(empty($sSql)){
								continue;
							}
							try{
								$oAdapter->execute($sSql, array());
							}
							catch (\Exception $ex)
							{
								$this->addError($sSql);
								$this->addError($ex->getMessage());
							}

						}
					}
				}
			}
        }
        catch (AppException $ex)
        {
        	//$this->_echo($ex->getMessage());
            $this->addError($ex->getMessage());
            echo "step_1";
            exit;
        }
        echo "Completed";
        exit;
    }

    public function checkRequirements()
    {
        //check php version
        $this->_echo("Check PHP Version ... ");
        if (version_compare(PHP_VERSION, '5.3.0', '<'))
        {
            $this->addError("PHP VERSION required 5.3.0 and newer");
        } else
        {
            $this->_echo("OK - PHP Version " . PHP_VERSION, 'success');
        }
        //check curl
        $this->_echo("Check PHP CURL ...");
        if (!function_exists('curl_init'))
        {
            $this->addError('CURL required');
        } else
        {
            $info = curl_version();

            $this->_echo("OK - CURL Version " . $info["version"], 'success');
        }
        $this->_echo("Check PHP Mysqli extension ...");
        if (!function_exists('mysqli_connect'))
        {
            $this->addError('MYSQLi extension required');
        } else
        {
            $this->_echo("OK", 'success');
        }
        //check configuration file
        $this->_echo("Check configuration file in " . APP_PATH_SETTING);
        $sFile = APP_PATH_SETTING . 'Config.default.php';
        if (!file_exists($sFile))
        {
            $this->addError("File Config.default.php is not existed. Please create new one");
        } else
        {
            $this->_echo("OK", "success");
        }
        $this->_echo("Check write permission ...");
        if (!is_writable(APP_PATH_SETTING))
        {
            $this->addError("Folder " . APP_PATH_SETTING . " has no write permission");
        } else
        {
            $this->_echo("OK", "success");
        }
        $aPublicChildrenFolders = array(
        		"Asset", "Cache", "Log","Minify","Upload"
        );
        $this->_echo("Check write permission for public folders ...");
        foreach($aPublicChildrenFolders as $sFolderName)
        {
        	$sPath = APP_PUBLIC_PATH . $sFolderName;
        	if (!is_writable($sPath))
        	{
        		$this->addError("Folder " . $sPath . " has no write permission");
        	}
        	else
        	{
        		$this->_echo($sFolderName.": OK", "success");
        	}
        }
        return !$this->hasError();
    }

    public function addError($sMessage, $bDump = true)
    {
        $this->_aErrors[] = $sMessage;
        if ($bDump)
        {
            $this->_echo($sMessage, "fail");
        }
        return $this;
    }

    public function hasError()
    {
        $bResult = (count($this->_aErrors) > 0) ? true : false;
        return $bResult;
    }

    public function next()
    {
        $iIndex = array_keys(self::$_aSteps[self::$_currentStep]);
        if ($iIndex > 0)
        {
            $iIndex++;
        }
        self::$_currentStep = isset(self::$_aSteps[$iIndex]) ? self::$_aSteps[$iIndex] : "completeInstallation";
        return self::$_currentStep;
    }

    public function prev()
    {
        $iIndex = array_keys(self::$_aSteps[self::$_currentStep]);
        if ($iIndex > 0)
        {
            $iIndex--;
        }
        self::$_currentStep = isset(self::$_aSteps[$iIndex]) ? self::$_aSteps[$iIndex] : "checkRequirements";
        return self::$_currentStep;
    }

    public function getCurrentUrl()
    {
        $sUrl = "{$_SERVER['HTTP_HOST']}/{$_SERVER['REQUEST_URI']}";
        $sUrl = str_replace('//', '/', $sUrl);
        $sUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $sUrl;
        return $sUrl;
    }

    public function _echo($sMessage, $sType = "command")
    {
        echo '<span class="echo_content ' . $sType . '">' . $sMessage . '</span>';
        ob_flush();
        flush();
        sleep(1);
    }

}
