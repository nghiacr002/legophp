<?php
namespace APP\Library\Database\Adapter;

class PDO implements \APP\Library\Database\Adapter
{
	private $_oDriver = null;
	private $_aErrors = array ();
	private $_sLastQuery = "";
	private $_aNoneAffectRowQuery = array (
			"SELECT",
			"DROP",
			"SHOW",
			"TRUNCATE"
	);
	const SUCCESS_CODE = '00000';
	public function connect($configs = array())
	{
		try
		{
			$sDBType = isset($configs['type']) ? $configs['type'] : "mysql";
			$sDSN = "";
			$sUserName = isset($configs['user']) ? $configs['user'] : "";
			$sPassword = isset($configs['pwd']) ? $configs['pwd'] : "";
			$sHost = isset($configs['host']) ? $configs['host'] : "";
			$sPort = isset($configs['port']) ? $configs['port'] : "";
			$sDBName = isset($configs['name']) ? $configs['name'] : "";
			$sCharset = isset($configs['charset']) ? $configs['charset'] : "";
			$aOptions = isset($configs['options']) ? $configs['options'] : null;
			$aCommands = array();
			switch($sDBType)
			{
				case 'pgsql':
                    $sDSN = $sDBType . ':host=' . $sHost . (!empty($sPort) ? ';port=' . $sPort : '') . ';dbname=' . $sDBName;
                    break;

                case 'sybase':
                    $sDSN = 'dblib:host=' . $sHost . (!empty($sPort) ? ':' . $sPort : '') . ';dbname=' . $sDBName;
                    break;

                case 'oracle':
                    $sConnectString = $sHost ?
                            '//' . $sHost . (!empty($sPort) ? ':' . $sPort : ':1521') . '/' . $sDBName :
                            $sDBName;

                    $sDSN = 'oci:dbname=' . $sConnectString . ($sCharset ? ';charset=' . $sCharset : '');
                    break;

                case 'mssql':
                    $sDSN = strstr(PHP_OS, 'WIN') ?
                            'sqlsrv:server=' . $sHost . (!empty($sPort) ? ',' . $sPort : '') . ';database=' . $sDBName :
                            'dblib:host=' . $sHost . (!empty($sPort) ? ':' . $sPort : '') . ';dbname=' . $sDBName;
                    $aCommands[] = 'SET QUOTED_IDENTIFIER ON';
                    break;

                case 'sqlite':
                    $sDSN = $sDBType . ':' . $sDBName;
                    $sUserName = null;
                    $sPassword = null;
                    break;
                case 'mariadb':
				case 'mysql':
					$sDSN = $sDBType . ':host=' . $sHost . (!empty($sPort) ? ';port=' . $sPort : '') . ';dbname=' . $sDBName;
					$aCommands[] = 'SET SQL_MODE=ANSI_QUOTES';
				default:
					break;
			}
			if(!empty($sDSN))
			{
				$this->_oDriver = new \PDO($sDSN,$sUserName, $sPassword, $aOptions);
				if($this->_oDriver)
				{
					$aCommands[] = "SET NAMES '" . $sCharset . "'";
					foreach ($aCommands as $sQuery)
					{
						$this->_oDriver->exec($sQuery);
					}
					/*if($sDBType == 'mysql')
					{
						$this->_oDriver->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
					}*/
				}
			}
		}
		catch(\PDOException $ex)
		{
			throw new \Exception("Could not connect to database with error [".$ex->getCode()."]". $ex->getMessage());
		}
	}

	public function disconnect()
	{
		$this->_oDriver = null;
	}

	public function execute($query, $bind_params = array())
	{
		if ($query instanceof \APP\Engine\Database\Query)
		{
			list ( $query, $bind_params ) = $query->build ();
		}
		$query = trim ( $query );
		$stmt = $this->_oDriver->prepare ( $query );
		$this->_sLastQuery = $query;
		$bNonAffectRow = false;

		foreach ( $this->_aNoneAffectRowQuery as $sKeyword )
		{
			if (strpos ( $query, $sKeyword ) === 0)
			{
				$bNonAffectRow = true;
				break;
			}
		}

		if (! $stmt)
		{
			$sMessage = "Invalid STMT Query Statement provider: " . implode("|",$this->_oDriver->errorInfo()) . " ";
			$sMessage .= $query;
			throw new \Exception ( $sMessage, HTTP_CODE_NOT_IMPLEMENTED );
		}
		try
		{
			$aParams = array();
			if (count ( $bind_params ))
			{
				// bind params
				$iCnt = 0;
				foreach ( $bind_params as $key => $param )
				{
					$iCnt++;
					$typeData = $this->_determineType ( $param );
					if($typeData == \PDO::PARAM_INT)
					{
						$param = (int)$param;
					}
					//$stmt->bindParam($iCnt, $param,$typeData);
					$aParams[] = $param;
				}
			}
			$stmt->execute ($aParams);
			$this->_sLastQuery = $stmt->queryString;
			$result = null;
			if ($stmt->errorCode() != self::SUCCESS_CODE &&  $stmt->errorCode() != 0)
			{
				$this->_aErrors = $stmt->errorInfo();
				throw new \Exception("Query error ". $stmt->errorCode());
			}
			else
			{
				$result = $this->_oDriver->lastInsertId();
				if($result > 0)
				{
					//insert
				}
				else
				{
					$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
				}
			}
		}
		catch(\Exception $ex)
		{
			throw new \Exception("Query error [". $ex->getCode()."]".$ex->getMessage()." : ". $this->_sLastQuery);
		}
		//$stmt->closeCursor();

		return $result;
	}

	public function hasError()
	{
		if (count ( $this->_aErrors ))
		{
			return true;
		}
		return false;
	}

	public function getDriverInfo()
	{
		return array (
				'client_version' => $this->_oDriver->getAttribute(\PDO::ATTR_CLIENT_VERSION),
				'host_info' => $this->_oDriver->getAttribute(\PDO::ATTR_DRIVER_NAME),
				'server_info' => $this->_oDriver->getAttribute(\PDO::ATTR_SERVER_VERSION),
		);
	}

	public function getErrors()
	{
		return $this->_aErrors;
	}

	public function escape($query)
	{
		return $this->_oDriver->quote($query);
	}

	public function getLastQuery()
	{
		return $this->_sLastQuery;
	}

	public function startTransaction()
	{
		$this->_oDriver->beginTransaction();
	}

	public function commitTransaction()
	{
		$this->_oDriver->commit();
	}

	public function rollback()
	{
		$this->_oDriver->rollBack();
	}
	protected function _determineType($item)
	{
		switch (gettype ( $item ))
		{
			case 'NULL' :
				return \PDO::PARAM_NULL;
			case 'string' :
				return \PDO::PARAM_STR;
			case 'boolean' :
				return \PDO::PARAM_BOOL;
			case 'integer' :
				return \PDO::PARAM_INT;
			case 'blod':
				return \PDO::PARAM_LOB;
		}
		return null;
	}
	protected function refValues($arr)
	{
		// Reference is required for PHP 5.3+
		if (strnatcmp ( phpversion (), '5.3' ) >= 0)
		{
			$refs = array ();
			foreach ( $arr as $key => $value )
			{
				$refs [$key] = & $arr [$key];
			}
			return $refs;
		}
		return $arr;
	}
}