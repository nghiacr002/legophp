<?php

namespace APP\Engine\Module;

use APP\Engine\Database\Query;
use APP\Engine\File;

class Exporter extends Component
{
	protected $_aDatabaseTables = array ();
	protected $_aCustomQueries = array ();
	protected $_sFileName = "";
	protected $_sBuildVersion = "";
	protected $_sTargetPath;
	const SPECTATOR = PHP_EOL."---------------------------------------".PHP_EOL;
	const HASH_PREFIX = "#_#####_#";
	public function target($sPath)
	{
		$this->_sTargetPath = $sPath;
	}
	public function buildVersion($sVersion)
	{
		$this->_sBuildVersion = $sVersion;
	}
	public function exportDB()
	{
		$aQueries = array ();
		if (count ( $this->_aDatabaseTables ))
		{
			foreach ( $this->_aDatabaseTables as $sTableName => $aConfig )
			{
				$aQuery = $this->_exportDBTable ( $sTableName, $aConfig );
				$aQueries = array_merge ( $aQueries, $aQuery );
			}
		}
		return $this->_saveSQL ( $aQueries );
	}
	protected  function _buildConditions($sTableName)
	{
		return array();
	}
	protected function _exportDBTable($sTableName, $aConfig = array())
	{
		$aQueries = array ();
		$db = $this->app ()->database;
		$oQuery = new Query ( "command" );
		$sFullTableName = \APP\Engine\Database\DbTable::getFullTableName ( $sTableName );
		// $oQuery->from($sFullTableName);
		$mData = $oQuery->select ( "SHOW CREATE TABLE " . $sFullTableName )->execute ();
		$sHashTable = self::HASH_PREFIX . $sTableName;
		$bIsQueryInseart = isset($aConfig['no-structure']) ? $aConfig['no-structure']: false;
		if (isset ( $mData [0] ['Create Table'] ) && !$bIsQueryInseart)
		{
			$sSQL = str_ireplace ( 'CREATE TABLE `', 'CREATE TABLE IF NOT EXISTS `', $mData [0] ['Create Table'] );
			$sSQL = str_ireplace ( '`' . $sFullTableName . '`', '`' . $sHashTable . '`', $sSQL );
			$aQueries [] = $sSQL . ';';
			$aQueries [] = "";
		}
		$bNoData = isset ( $aConfig ['no-data'] ) ? $aConfig ['no-data'] : false;
		if ($bNoData == false)
		{
			$iLimit = 100;
			$iPage = 1;
			do
			{
				$oQuery = new Query ( "Select" );
				$aConds = $this->_buildConditions($sTableName);
				if(is_array($aConds) && count($aConds))
				{
					foreach($aConds as $iKey => $aCond)
					{
						$param = isset($aCond[0]) ? $aCond[0] : "";
						$bind = isset($aCond[1]) ? $aCond[1] : "";
						$operator = isset($aCond[2]) ? $aCond[2] : "=";
						$cond_type = isset($aCond[3]) ? $aCond[3] : "AND";
						$oQuery->where($param,$bind,$operator,$cond_type);
					}
				}
				$aRows = $oQuery->select ( '*' )->from ( $sFullTableName )->limit ( $iPage, $iLimit )->execute ();
				if (! is_array ( $aRows ) || ! count ( $aRows ))
				{
					break;
				}
				$aDataContent = array ();
				foreach ( $aRows as $aRow )
				{
					$sContent = [ ];
					foreach ( $aRow as $iKey => $sData )
					{
						$sData = addslashes ( $sData );
						$sData = '"' . $sData . '"';
						$sContent [] = $sData;
					}
					$sContent = '(' . implode ( ',', $sContent ) . ')';
					$aDataContent [] = $sContent;
				}
				$sDataContent = implode ( ',' . PHP_EOL, $aDataContent );
				$iPage ++;
				$aQueries [] = 'INSERT IGNORE INTO `' . $sHashTable . '` VALUES' . $sDataContent . ';';
				$aQueries [] = "";
			} while ( 1 == 1 );
		}
		return $aQueries;
	}
	protected function _saveSQL($aQueries = array())
	{
		$sFileName = $this->_sFileName;
		if (empty ( $sFileName ))
		{
			$sFileName = uniqid ();
		}
		$sFileName .= "_" . $this->_sBuildVersion . '.sql';
		$sFileName = $this->_sTargetPath . APP_DS . 'Sql' . APP_DS . $sFileName;
		$oFile = (new File ());
		$oFile->write ( $sFileName, implode ( self::SPECTATOR, $aQueries ) );
		if ($this->_aCustomQueries && count ( $this->_aCustomQueries ))
		{
			$oFile->append ( $sFileName, $this->_aCustomQueries );
		}
		return true;
	}
}