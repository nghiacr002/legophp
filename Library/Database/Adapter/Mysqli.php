<?php

namespace APP\Library\Database\Adapter;

class Mysqli implements \APP\Library\Database\Adapter
{
	private $_oDriver = null;
	private $_aErrors = array ();
	private $_aNoneAffectRowQuery = array (
			"SELECT",
			"DROP",
			"SHOW",
			"TRUNCATE"
	);
	private $_sLastQuery = "";
	public function connect($configs = array())
	{
		$this->_oDriver = mysqli_connect ( $configs ['host'], $configs ['user'], $configs ['pwd'], $configs ['name'], $configs ['port'] );
		if ($this->_oDriver->connect_error)
		{
			throw new Exception ( 'Mysqli connect error ' . $this->_oDriver->connect_errno . ': ' . $this->_oDriver->connect_error );
		}
		$this->_oDriver->set_charset ( $configs ['charset'] );
		return $this;
	}
	public function disconnect()
	{
		if ($this->_oDriver)
		{
			$this->_oDriver->close ();
		}
	}
	public function escape($query)
	{
		return $this->_oDriver->real_escape_string ( $query );
	}
	public function execute($query, $bind_params = array())
	{
		if ($query instanceof \APP\Engine\Database\Query)
		{
			list ( $query, $bind_params ) = $query->build ();
		}
		$query = trim ( $query );
		$stmt = $this->_oDriver->prepare ( $query );

		$bNonAffectRow = false;

		foreach ( $this->_aNoneAffectRowQuery as $sKeyword )
		{
			if (strpos ( $query, $sKeyword ) === 0)
			{
				$bNonAffectRow = true;
				break;
			}
		}
		$this->_sLastQuery = $query;
		if (! $stmt)
		{
			$sMessage = "Invalid STMT Query Statement provider: " . $this->_oDriver->error . " ";
			$sMessage .= $query;
			throw new \Exception ( $sMessage, HTTP_CODE_NOT_IMPLEMENTED );
		}
		if (count ( $bind_params ))
		{
			// bind params
			$stype = "";
			foreach ( $bind_params as $key => $param )
			{
				$stype .= $this->_determineType ( $param );
			}
			$bind_params = array_merge ( array (
					'stmtbindtype' => $stype
			), $bind_params );
			call_user_func_array ( array (
					$stmt,
					'bind_param'
			), $this->refValues ( $bind_params ) );
		}

		$stmt->execute ();
		$result = null;
		if ($stmt->errno)
		{
			$this->_aErrors = $stmt->error_list;
			throw new \Exception ( $this->_aErrors [0] ['error'], $this->_aErrors [0] ['errno'] );
		} else
		{
			$result = $this->getResult ( $stmt, $bNonAffectRow );
		}
		$stmt->close ();
		return $result;
	}
	public function getLastQuery()
	{
		return $this->_sLastQuery;
	}
	public function getDriverInfo()
	{
		return array (
				'client_version' => $this->_oDriver->client_info,
				'host_info' => $this->_oDriver->host_info,
				'server_info' => $this->_oDriver->server_info
		);
	}
	public function getErrors()
	{
		return $this->_aErrors;
	}
	public function hasError()
	{
		if (count ( $this->_aErrors ))
		{
			return true;
		}
		return false;
	}
	public function startTransaction()
	{
		$this->_oDriver->autocommit ( FALSE );
	}
	public function commitTransaction()
	{
		$this->_oDriver->commit ();
	}
	public function rollback()
	{
		$this->_oDriver->rollback ();
	}
	protected function getResult($stmt = null, $bNonAffectRow = true)
	{
		if ($stmt->insert_id > 0)
		{
			return $stmt->insert_id;
		}
		if (! $bNonAffectRow)
		{
			if ($stmt->affected_rows == 0 && $stmt->errno == 0)
			{
				return true;
			}
			if ($stmt->affected_rows < 1)
			{
				return false;
			}
			return true;
		}
		$meta = $stmt->result_metadata ();
		if (! $meta && $stmt->sqlstate)
		{
			return array ();
		}

		if (version_compare ( phpversion (), '5.4', '<' ))
		{
			$stmt->store_result ();
		}
		if (method_exists ( $stmt, 'get_result' ))
		{
			$result = $stmt->get_result ();
			$values = $result->fetch_all ( MYSQLI_ASSOC );
		} else
		{
			$values = array ();
			while ( $row = $this->fetchAssocStatement ( $stmt ) )
			{
				// do your magic
				$values [] = $row;
			}
		}
		return $values;
	}
	private function fetchAssocStatement($stmt)
	{
		if ($stmt->num_rows > 0)
		{
			$result = array ();
			$md = $stmt->result_metadata ();
			$params = array ();
			while ( $field = $md->fetch_field () )
			{
				$params [] = &$result [$field->name];
			}
			call_user_func_array ( array (
					$stmt,
					'bind_result'
			), $params );
			if ($stmt->fetch ())
				return $result;
		}

		return null;
	}
	protected function _determineType($item)
	{
		switch (gettype ( $item ))
		{
			case 'NULL' :
			case 'string' :
				return 's';
				break;
			case 'boolean' :
			case 'integer' :
				return 'i';
				break;
			case 'blob' :
				return 'b';
				break;
			case 'double' :
				return 'd';
				break;
		}
		return '';
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
