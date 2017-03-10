<?php
use APP\Engine\Database\DbTable;
use APP\Engine\File;
use APP\Engine\Application;
use APP\Application\Module\Core\Model\Module as ModuleModel;

define ( 'SIMPLE_APP', true );
define ( 'APP_DS', DIRECTORY_SEPARATOR );
define ( 'APP_ROOT_PATH', dirname ( dirname ( __FILE__ ) ) . APP_DS );
define ( 'APP_PATH', APP_ROOT_PATH . "Application" . APP_DS );
define ( 'APP_PATH_SETTING', APP_PATH . 'Setting' . APP_DS );
define ( 'APP_PATH_LIB', APP_ROOT_PATH . 'Library' . APP_DS );
define ( 'APP_PATH_DEV_TOOLS', dirname ( __FILE__ ) );
require_once APP_PATH_SETTING . 'Loader.php';
$mainApp = new APP\Engine\Application ( $_CONF );
try
{
	$mainApp->init ();
	(new DEVTOOLS ( $mainApp ))->process ();
} catch ( Exception $ex )
{
	echo "\r\n" . $ex->getMessage () . "\r\n";
	exit ();
}
class DEVTOOLS
{
	private $_aParams = array ();
	private $app;
	private $aLogContent = array ();
	public function __construct(Application $app)
	{
		$this->app = $app;
		$argv = isset ( $_SERVER ['argv'] ) ? $_SERVER ['argv'] : array ();
		$this->_aParams = array ();
		if (count ( $argv ) >= 2)
		{
			$this->_aParams ['file'] = $argv [0];
			array_shift ( $argv );
			$this->_aParams ['cmd'] = isset ( $argv [0] ) ? $argv [0] : "";
			if (! empty ( $this->_aParams ['cmd'] ))
			{
				array_shift ( $argv );
				foreach ( $argv as $sParam )
				{
					$aParts = explode ( '=', $sParam );
					if (count ( $aParts ) == 2)
					{
						$this->_aParams [trim ( $aParts [0] )] = trim ( $aParts [1] );
					}
				}
			}
		}
	}
	public function setParam($sKey, $mValue)
	{
		$this->_aParams[$sKey] = $mValue;
		return $this;
	}
	public function process()
	{
		$task = $this->get ( 'cmd' );
		// $sMethod = "create".ucfirst($task);
		$this->display ( $task );
		if (method_exists ( $this, $task ))
		{
			$this->{$task} ();
		} else
		{
			$this->display ( "No function found" );
			$this->help ();
		}
	}
	public function get($sName)
	{
		return isset ( $this->_aParams [$sName] ) ? $this->_aParams [$sName] : "";
	}
	public function releaseCoreEngine()
	{
		$this->_aParams['exclude-module'] = "Blog";
		return $this->releaseApp();
	}
	public function releaseApp()
	{
		define ( 'LOG_TO_FILE', true );
		$bIncreaseVersion = $this->get ( 'increase-version' ) ? true : false;
		$sNewVersion = $this->getBuildNumber ( $bIncreaseVersion );
		$this->display ( "Build App Core Release --- " . $sNewVersion );
		$sExcludedModule = $this->get ( 'exclude-module' );
		$sReleasePath = $this->get ( 'target' );
		if (empty ( $sReleasePath ))
		{
			$sReleasePath = APP_ROOT_PATH . 'Release';
		}
		$sReleasePath = $sReleasePath . APP_DS . $sNewVersion;
		// create folder
		if (! is_dir ( $sReleasePath ))
		{
			@mkdir ( $sReleasePath, 0755 );
		}
		// Part will be copied
		$aFolders = array (
				'Application',
				'DevTool',
				'Engine',
				'Library',
				'Public',
				'Install',
				'.htaccess',
				'index.php',
				'install.php',
				'unit-test.php'
		);

		$oFile = (new File ());
		foreach ( $aFolders as $sFolder )
		{
			$sSourcePath = APP_ROOT_PATH . $sFolder;
			$sDestinationPath = $sReleasePath . APP_DS . $sFolder;
			$this->display ( "Copy " . $sFolder );
			if (is_file ( $sSourcePath ))
			{
				@copy ( $sSourcePath, $sDestinationPath );
			} else
			{
				$oFile->recurse_copy ( $sSourcePath, $sDestinationPath );
			}
		}

		// remove unwanted module
		if (! empty ( $sExcludedModule ))
		{
			$sExcludedModule = strtolower ( $sExcludedModule );
			$aExcludedModule = explode ( ',', $sExcludedModule );

			foreach ( $aExcludedModule as $sModuleName )
			{
				$sPath = $sReleasePath . APP_DS . "Application" . APP_DS . "Module" . APP_DS . ucfirst ( $sModuleName );
				if (is_dir ( $sPath ))
				{
					$oFile->recurse_remove ( $sPath );
				}
			}
		}

		// export database
		$aInstalledModules = $this->app->module->getInstalledModules ();
		foreach ( $aInstalledModules as $iKey => $oModule )
		{
			if (isset ( $aExcludedModule ) && count ( $aExcludedModule ))
			{
				if (in_array ( strtolower ( $oModule->module_name ), $aExcludedModule ))
				{
					continue;
				}
			}
			$oExporter = $oModule->exporter ();
			if ($oExporter)
			{
				$oExporter->buildVersion ( $sNewVersion );
				$oExporter->target ( $sReleasePath . APP_DS . "Install" );
				$oExporter->exportDB ();
				$this->display ( "Export DB for module " . $oModule->module_name );
			}
		}
		//clean up unwanted file
		$aPublicChildrenFolders = array(
			"Asset", "Cache", "Log","Minify","Upload"
		);
		foreach($aPublicChildrenFolders as $sFolderName)
		{
			$sPath = $sReleasePath . APP_DS . "Public" . APP_DS . $sFolderName;
			if (is_dir ( $sPath ))
			{
				$oFile->recurse_remove ( $sPath,false );
			}
		}
		$sPath = $sReleasePath . APP_DS . "Application" . APP_DS . "Setting" . APP_DS . "Config.php";
		$oFile->removeFile($sPath);
		$sPath = $sReleasePath . APP_DS . "Application" . APP_DS . "Setting" . APP_DS . "Debug.php";
		$oFile->removeFile($sPath);

		$sFileConfig = $sReleasePath . APP_DS . "Application" . APP_DS . "Setting" . APP_DS. 'Config.default.php';
		$sCurrentFileDefaultConfig = APP_PATH_SETTING .'Config.default.php';
		include_once $sCurrentFileDefaultConfig;
		$aConfigs = $_CONF;
		$aConfigs['apps']['version'] = $sNewVersion;
		$sAppName = $this->get('app-name');
		if(!empty($sAppName))
		{
			$aConfigs['apps']['name'] = $sAppName;
		}
		$sConfigString = '<?php $_CONF = ' . var_export($aConfigs, true) . '?>';

        @file_put_contents($sFileConfig, $sConfigString);
		// create build.log
		file_put_contents ( $sReleasePath . APP_DS . 'build.log', implode ( PHP_EOL, $this->aLogContent ) );
	}

	public function getBuildNumber($bIncreaseVersion = false)
	{
		$sAppVersionConfig = APP_PATH_DEV_TOOLS . APP_DS . 'build.app.version';

		if (! file_exists ( $sAppVersionConfig ))
		{
			throw new \Exception ( "App Version file not found" );
		}
		$sContent = file_get_contents ( $sAppVersionConfig );
		if ($this->get ( 'overwrite' ))
		{
			return $sContent;
		}
		$aParts = explode ( '.', $sContent );
		if (count ( $aParts ) != 3)
		{
			throw new \Exception ( "Invalid format app version file" );
		}
		$sTime = date ( 'dmY' );
		if ($bIncreaseVersion == true)
		{
			$aParts = array (
					$aParts [0] + 1,
					'00',
					$sTime
			);
		} else
		{
			$aParts [1] = ( int ) $aParts [1] + 1;
			$aParts [2] = $sTime;
			if ($aParts [1] < 10)
			{
				$aParts [1] = "0" . $aParts [1];
			} else if ($aParts [1] > 99)
			{
				$aParts [0] = $aParts [0] + 1;
				$aParts [1] = "00";
			}
		}
		$sNewVersion = implode ( '.', $aParts );
		@file_put_contents ( $sAppVersionConfig, $sNewVersion );
		return $sNewVersion;
	}
	public function help()
	{
	}
	public function createModule()
	{
		$sModule = $this->get ( 'name' );
		$sModulePath = APP_MODULE_PATH . ucfirst ( $sModule ) . APP_DS;
		if(empty($sModule))
		{
			$this->display("Require module name");
			exit;
		}
		if(file_exists($sModulePath))
		{
			$this->display("Module folder has been existed");
			exit;
		}
		$oFile = new File();
		$oFile->createIfMissingFolder($sModulePath, APP_MODULE_PATH);

		//create module structure
		$aFolders = array(
			'Widget',
			'Controller',
			'Lanaguage',
			'Model' => array(
				'DbTable' => array(
						'DbRow'
				)
			),
			'Plugin',
			'Public' => array(
				'Css',
				'Js',
				'Img'
			),
			'UnitTest',
			'View' => array(
				'Default' => array(
					'Controller',
					'Widget'
				)
			),

		);
		foreach($aFolders as $sKey1 => $aSubFolders)
		{
			$bHasSub = false;
			$sName = $sKey1;
			if(is_array($aSubFolders) && count($aSubFolders))
			{
				$sPath = $sModulePath .APP_DS .  $sKey1;
				$bHasSub = true;
			}
			else
			{
				$sName =  $aSubFolders;
			}
			$this->_createFolder($sKey1, $aSubFolders, $sModulePath);
		}
		//create base files
		$this->display("create default index controller");
		$this->setParam('module', $sModule);
		$this->setParam('name', 'index');
		$this->createBootstrap();
		$this->createController();
		//insert database
		$this->display("Register module with system");
		$oModuleModel = new ModuleModel();
		$oExistedModule = $oModuleModel->getOne(strtolower($sModule),'module_name');
		if(!$oExistedModule)
		{
			$oItem = $oModuleModel->getTable()->createRow(array(
				'module_name' => strtolower($sModule),
				'module_title' => $sModule,
				'module_version' => '1.0',
				'owner' => 'lego',
			));
			$oItem->save();
		}
		else
		{
			$this->display("This module has been registered");
		}
	}
	public function createBootstrap()
	{
		$sModule = $this->get ( 'module' );
		$this->display("Create bootstrap file for module ". $sModule);
		$sModulePath = APP_MODULE_PATH . ucfirst ( $sModule ) . APP_DS;
		$sBootstrapFile = $sModulePath . "Bootstrap.php";
		$aContent = array ();
		$aContent [] = '<?php ';
		$aContent [] = 'namespace APP\Application\Module\\' . $sModule . ';';
		$aContent [] = 'use APP\Engine\Module\Bootstrap as ModuleBootstrap;';
		$aContent [] = 'class Bootstrap extends ModuleBootstrap';
		$aContent [] = '{';
		$aContent [] = '	protected function initTemplate()';
		$aContent [] = '	{';
		$aContent [] = '	}';
		$aContent [] = '	protected function initLanguage()';
		$aContent [] = '	{';
		$aContent [] = '	}';
		$aContent [] = '}';
		$this->writeFile ( $sBootstrapFile, $aContent );
	}
	protected function _createFolder($sName, $mKey = null, $sBasePath = null)
	{
		$oFile = new File();

		if(is_numeric($sName))
		{
			$sPath = $sBasePath .APP_DS .  $mKey;
			$oFile->createIfMissingFolder($sPath, $sBasePath);
			return;
		}
		if(is_array($mKey) && count($mKey))
		{
			$sBaseNewPath = $sBasePath . APP_DS . $sName;
			$oFile->createIfMissingFolder($sBaseNewPath, $sBasePath);
			foreach($mKey as $sName2 => $mKey2)
			{
				$this->_createFolder($sName2, $mKey2, $sBaseNewPath);
			}
		}
		return true;
	}
	public function createWidget()
	{
		$sModule = $this->get ( 'module' );
		$sModule = ucfirst ( $sModule );
		$sWidget = $this->get ( 'name' );
		$sWidget = ucfirst ( $sWidget );
		if (empty ( $sWidget ))
		{
			$this->display ( "Widget not found", true );
		}
		$sModulePath = APP_MODULE_PATH . ucfirst ( $sModule );
		if (empty ( $sModule ) || ! file_exists ( $sModulePath ))
		{
			$this->display ( "Module not found", true );
			exit ();
		}
		$oFile = new File ();
		$sModuleWidgetPath = $sModulePath . APP_DS . 'Widget';
		$oFile->createIfMissingFolder ( $sModuleWidgetPath, $sModulePath );
		$sFileModel = $sModuleWidgetPath . APP_DS . $sWidget . '.php';
		$sClassName = $sWidget . "Widget";
		$aContent = array ();
		$aContent [] = '<?php ';
		$aContent [] = 'namespace APP\Application\Module\\' . $sModule . ';';
		$aContent [] = 'use APP\Engine\Module\Widget;';
		$aContent [] = 'class ' . $sClassName . ' extends Widget';
		$aContent [] = '{';
		$aContent [] = '	public function process()';
		$aContent [] = '	{';
		$aContent [] = '	}';
		$aContent [] = '}';
		$this->writeFile ( $sFileModel, $aContent );
		// create view
		$aContent = array ();
		$sTheme = $this->get ( 'theme' );
		if (empty ( $sTheme ))
		{
			$sTheme = "Default";
		}
		$sModuleWidgetPath = $sModulePath . APP_DS . 'View' . APP_DS . $sTheme . APP_DS . 'Widget';
		$oFile->createIfMissingFolder ( $sModuleWidgetPath, $sModulePath );
		$sFileModel = $sModuleWidgetPath . APP_DS . $sWidget . '.tpl';
		@chmod ( $sModuleWidgetPath, 0755 );
		$this->writeFile ( $sFileModel, array () );
	}
	public function createController()
	{
		$sModule = $this->get ( 'module' );
		$sModule = ucfirst ( $sModule );
		$sController = $this->get ( 'name' );
		if (empty ( $sController ))
		{
			$this->display ( "Controller not found", true );
		}
		$sModulePath = APP_MODULE_PATH . ucfirst ( $sModule );
		if (empty ( $sModule ) || ! file_exists ( $sModulePath ))
		{
			$this->display ( "Module not found", true );
			exit ();
		}
		$oFile = new File ();
		$sModuleControllerPath = $sModulePath . APP_DS . 'Controller';
		$oFile->createIfMissingFolder ( $sModuleControllerPath, $sModulePath );
		$sController = ucfirst ( $sController );
		if ($this->get ( 'admincp' ) == true)
		{
			$sController = "Admin" . $sController;
		}
		$sFileModel = $sModuleControllerPath . APP_DS . $sController . '.php';
		$sClassName = $sController . "Controller";
		$aContent = array ();
		$aContent [] = '<?php ';
		$aContent [] = 'namespace APP\Application\Module\\' . $sModule . ';';
		$aContent [] = 'use APP\Engine\Module\Controller;';
		$aContent [] = 'class ' . $sClassName . ' extends Controller';
		$aContent [] = '{';

		$aContent [] = '}';
		$this->writeFile ( $sFileModel, $aContent );
		// create view folder
		$aContent = array ();
		$sTheme = $this->get ( 'theme' );
		if (empty ( $sTheme ))
		{
			$sTheme = "Default";
		}
		$sModuleControllerPath = $sModulePath . APP_DS . 'View' . APP_DS . $sTheme . APP_DS . 'Controller' . APP_DS . $sController;
		$this->display ( 'create view ' . $sModuleControllerPath );
		$oFile->createIfMissingFolder ( $sModuleControllerPath, $sModulePath );
		@chmod ( $sModuleControllerPath, 0755 );

		//create unit test
		$this->display("create unit-test file for controller ". $sController);
		$sPathUnitTest = $sModulePath.APP_DS. "UnitTest". APP_DS . "Controller";
		$oFile->createIfMissingFolder($sPathUnitTest, $sModulePath);
		$sControllerTestName = $sController . "Test";
		$sFileUnitTest = $sPathUnitTest . APP_DS . $sControllerTestName . ".php";
		$sClassName = $sControllerTestName;
		$aContent = array ();
		$aContent [] = '<?php ';
		$aContent [] = 'namespace PHPUnit\\Framework\\TestCase;';
		$aContent [] = 'class ' . $sClassName . ' extends TestCase';
		$aContent [] = '{';
		$aContent [] = '}';
		$this->writeFile ( $sFileUnitTest, $aContent );

	}
	public function createModel()
	{
		$sTable = $this->get ( 'table' );
		$sModule = $this->get ( 'module' );
		$sModule = ucfirst ( $sModule );
		if (empty ( $sTable ))
		{
			$this->display ( "Table not found", true );
		}
		$sModulePath = APP_MODULE_PATH . ucfirst ( $sModule );
		if (empty ( $sModule ) || ! file_exists ( $sModulePath ))
		{
			$this->display ( "Module not found", true );
			exit ();
		}
		$oTable = new DbTable ( $sTable );
		$aColumns = $oTable->getColumns ();
		if (count ( $aColumns ))
		{
			// create model path
			$oFile = new File ();
			$sModuleModelPath = $sModulePath . APP_DS . 'Model';
			$oFile->createIfMissingFolder ( $sModuleModelPath, $sModulePath );

			$sClassModelName = $this->get ( 'class' );
			if (empty ( $sClassModelName ))
			{
				$sClassModelName = $sTable;
			}
			$sClassModelName = ucfirst ( $sClassModelName );
			// write model file
			$sFileModel = $sModuleModelPath . APP_DS . $sClassModelName . '.php';
			$aContent [] = '<?php ';
			$aContent [] = 'namespace APP\Application\Module\\' . $sModule . '\Model;';
			$aContent [] = 'use APP\Engine\Module\Model;';
			$aContent [] = 'use APP\Application\Module\\' . $sModule . '\Model\DbTable\\' . $sClassModelName . ' as Db' . $sClassModelName . ';';
			$aContent [] = 'class ' . $sClassModelName . ' extends Model';
			$aContent [] = '{';
			$aContent [] = '	public function __construct()';
			$aContent [] = '	{';
			$aContent [] = '		$this->_oTable = new Db' . $sClassModelName . '();';
			$aContent [] = '		parent::__construct();';
			$aContent [] = '	}';
			$aContent [] = '}';
			$this->writeFile ( $sFileModel, $aContent );
			// write model dbtable
			$sModuleModelDbTablePath = $sModuleModelPath . APP_DS . "DbTable";
			$oFile->createIfMissingFolder ( $sModuleModelDbTablePath, $sModulePath );
			$mPrimaryKey = array ();
			$aRequiredFields = array ();
			foreach ( $aColumns as $sFieldName => $aColumn )
			{
				if ($aColumn ['Key'] == "PRI")
				{
					$mPrimaryKey [] = $sFieldName;
				} elseif ($aColumn ['Null'] == "NO")
				{
					$aRequiredFields [] = 'array(\'' . $sFieldName . '\')';
				}
			}
			if (count ( $mPrimaryKey ) > 1)
			{
				$mPrimaryKey = 'array(' . implode ( ',', $mPrimaryKey ) . ')';
			} else
			{
				$mPrimaryKey = $mPrimaryKey [0];
			}
			$aContent = array ();
			$sFileModel = $sModuleModelDbTablePath . APP_DS . $sClassModelName . '.php';
			$aContent [] = '<?php ';
			$aContent [] = 'namespace APP\Application\Module\\' . $sModule . '\Model\DbTable;';
			$aContent [] = 'class ' . $sClassModelName . ' extends \APP\Engine\Database\DbTable';
			$aContent [] = '{';
			$aContent [] = '	protected $_sTableName = "' . $sTable . '";';
			$aContent [] = '	protected $_mPrimaryKey = "' . $mPrimaryKey . '";';
			if (! empty ( $aRequiredFields ))
			{
				$aRequiredFields = implode ( ',', $aRequiredFields );
				$aContent [] = '	protected $_aValidateRules = array(';
				$aContent [] = '		\'required\' => array(';
				$aContent [] = '			' . $aRequiredFields;
				$aContent [] = '		)';
				$aContent [] = '	);';
				$aContent [] = '	protected $_sRowClass = "\\APP\\Application\\Module\\' . $sModule . '\Model\DbTable\\DbRow\\' . $sClassModelName . '";';
			}
			$aContent [] = '}';
			$this->writeFile ( $sFileModel, $aContent );
			// write model dbrow
			$aContent = array ();
			$sModuleModelDbTableRowPath = $sModuleModelDbTablePath . APP_DS . "DbRow";
			$oFile->createIfMissingFolder ( $sModuleModelDbTableRowPath, $sModulePath );
			$sFileModel = $sModuleModelDbTableRowPath . APP_DS . $sClassModelName . '.php';
			$aContent [] = '<?php ';
			$aContent [] = 'namespace APP\Application\Module\\' . $sModule . '\Model\DbTable\DbRow;';
			$aContent [] = 'class ' . $sClassModelName . ' extends \APP\Engine\Database\DbRow';
			$aContent [] = '{';
			$aContent [] = '}';
			$this->writeFile ( $sFileModel, $aContent );
			//create unit test
			$this->display("create unit-test file for model ". $sClassModelName);
			$sPathUnitTest = $sModulePath.APP_DS. "UnitTest". APP_DS . "Model";
			$oFile->createIfMissingFolder($sPathUnitTest, $sModulePath);
			$sControllerTestName = $sClassModelName . "Test";
			$sFileUnitTest = $sPathUnitTest . APP_DS . $sControllerTestName . ".php";
			$sClassName = $sControllerTestName;
			$aContent = array ();
			$aContent [] = '<?php ';
			$aContent [] = 'namespace PHPUnit\\Framework\\TestCase;';
			$aContent [] = 'class ' . $sControllerTestName . ' extends TestCase';
			$aContent [] = '{';
			$aContent [] = '}';
			$this->writeFile ( $sFileUnitTest, $aContent );
		}

	}
	private function writeFile($sFileName, $aContent)
	{
		$this->display ( 'create file ' . $sFileName );
		$fp = fopen ( $sFileName, 'w' );
		if ($fp)
		{
			foreach ( $aContent as $iKey => $sContent )
			{
				fwrite ( $fp, $sContent . "\r\n" );
			}
			@fclose ( $fp );
		}
	}
	public function display($sMessage, $bForceStop = false)
	{
		echo ("\r\n" . $sMessage . "\r\n");
		if (defined ( 'LOG_TO_FILE' ) && LOG_TO_FILE)
		{
			$this->aLogContent [] = $sMessage;
		}
		if ($bForceStop)
		{
			exit ();
		}
	}
}
