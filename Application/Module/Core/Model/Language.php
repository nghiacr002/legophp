<?php

namespace APP\Application\Module\Core\Model;

use APP\Application\Module\Core\Model\DbTable\Language as DbLanguage;
use APP\Engine\Application;
use APP\Engine\Module\Model;
use APP\Engine\Database\Query;

class Language extends Model
{

    public function __construct()
    {
        $this->_oTable = new DbLanguage();
        parent::__construct();
    }

    public function getAll($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "*", $mOrder = null)
    {
        $sCacheName = $this->_oTable->getTableName();
        $sCacheName = $sCacheName . md5($sSelectFields . $mOrder . $iPage . $iLimit);
        if ($aRows = $this->cache()->get($sCacheName,"Model"))
        {
            return $aRows;
        }
        $aRows = parent::getAll($aConds, $iPage, $iLimit, $sSelectFields, $mOrder);
        if ($aRows)
        {
            $this->cache()->set($sCacheName, $aRows, $this->getTTL(), "Model");
        }
        return $aRows;
    }

    public function getDefaultLanguage()
    {
        return $this->getOne(1, 'is_default');
    }

    public function setDefault($iLanguageId)
    {
        //set off all
        $oQuery = new Query("update");
        $oQuery->setTableData($this->getTable()->getTableName(), array(
            'is_default' => 0,
        ));
        $bResult1 = $this->getTable()->executeQuery($oQuery);
        $oQuery->clean();
        $oQuery->where('language_id', $iLanguageId);
        $oQuery->setTableData($this->getTable()->getTableName(), array(
            'is_active' => 1,
            'is_default' => 1,
        ));
        $bResult2 = $this->getTable()->executeQuery($oQuery);
        return $bResult2;
    }
	public function getPhraseVarName($sString)
	{
		$sString = preg_replace('/\s+/', '_', $sString);
		return $sString;
	}
	public function getPhrases($sLanguageCode = "")
	{
		$aPhrases = array();
		$aConds = array();
		if(empty($sLanguageCode))
		{
			$aLanguages = $this->getAll();
			$aPhrases = array();
			foreach($aLanguages as $iKey => $oLanguage)
			{
				$aPhrases[$oLanguage->language_code] = $this->fetchLanguagePhrases($oLanguage->language_code);
			}
			$aPatchLanguages = (new LanguagePatch())->getAllWithCache($aConds, null, null);
			$aPhrases = array_merge_recursive($aPhrases,$aPatchLanguages);
		}
		else
		{
			$aPhrases =  $this->fetchLanguagePhrases($sLanguageCode);
			$aConds = array(
					array('language_code',$sLanguageCode)
			);
			$aPatchLanguages = (new LanguagePatch())->getAllWithCache($aConds, null, null);
			$aPatchLanguages = isset($aPatchLanguages[$sLanguageCode]) ? $aPatchLanguages[$sLanguageCode] : array();
			$aPhrases = array_merge($aPhrases,$aPatchLanguages);
		}
		return $aPhrases;
	}
	public function fetchLanguagePhrases($sCode)
	{
		$aModules = $this->app->module->getInstalledModules();
		$aReturn = array();
		foreach($aModules as $iKey => $aModule)
		{
			$sFileName = APP_MODULE_PATH . ucfirst($aModule->module_name) . APP_DS . "Language" . APP_DS . strtolower($sCode) . ".php";
			if (file_exists($sFileName))
			{
				include  $sFileName;
				if (isset($aPhrases))
				{
					$aReturn = array_merge($aReturn,$aPhrases) ;
				}
			}
		}
		return $aReturn;

	}
}
