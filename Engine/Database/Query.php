<?php

namespace APP\Engine\Database;

class Query
{
	protected $_aConds = array ();
	protected $_aSelects = array ();
	protected $_aJoins = array ();
	protected $_aGroupBys = array ();
	protected $_aOrders = array ();
	protected $_aHavings = array ();
	protected $_sSQL = "";
	protected $_sCommand = "";
	protected $_sTableName;
	protected $_aTableData;
	protected $_aBindParams;
	protected $_aMainFrom;
	protected $_aLimit;
	public function __construct($sCommand = "")
	{
		$this->setCommand ( $sCommand );
	}
	public function execute()
	{
		$oAdapter = \APP\Engine\Database::getInstance ()->getAdapter ();
		list ( $sSql, $aBindParams ) = $this->build ();
		$mResult = $oAdapter->execute ( $sSql, $aBindParams );
		return $mResult;
	}
	public function setQuery($sQuery, $aBindParams = array())
	{
		$this->_sSQL = $sQuery;
		$this->_aBindParams = $aBindParams;
		return $this;
	}
	public function setCommand($sCommand = "")
	{
		$sCommand = trim ( $sCommand );
		$sCommand = strtoupper ( $sCommand );
		$this->_sCommand = $sCommand;

		return $this;
	}
	public function setTableData($sTableName, $aData)
	{
		$this->_sTableName = $sTableName;
		$this->_aTableData = $aData;
		return $this;
	}
	public function setFieldValue($sField, $mValue)
	{
		if (is_array ( $mValue ))
		{
			$mValue = serialize ( $mValue );
		}
		$this->_aTableData [$sField] = $mValue;
		return $this;
	}
	public function build()
	{
		if (empty ( $this->_sSQL ))
		{
			$sSql = $this->_sCommand . " ";
			$aBindParams = array ();
			switch ($this->_sCommand)
			{
				case "INSERT" :
					$sSql .= ' INTO ' . $this->_sTableName . '(`' . implode ( array_keys ( $this->_aTableData ), '`, `' ) . '`)';
					if (count ( $this->_aTableData ))
					{
						foreach ( $this->_aTableData as $mColumnName => $mValue )
						{
							$aValues [] = "?";
							$aBindParams [$mColumnName] = $mValue;
						}
					}
					$sSql .= 'VALUES(' . implode ( ',', $aValues ) . ')';
					break;
				case "UPDATE" :
					$aValues = array ();
					$sSql .= $this->_sTableName . " ";
					if (count ( $this->_aTableData ))
					{
						foreach ( $this->_aTableData as $mColumnName => $mValue )
						{
							$aValues [] = $mColumnName . ' = ?';
							$aBindParams [$mColumnName] = $mValue;
						}
					}
					$sSql .= " SET " . implode ( ',', $aValues );
					$where = $this->_buildWhere ();

					$sSql .= " WHERE " . $where;
					if (is_array ( $this->_aBindParams ))
					{
						$aBindParams = array_merge ( $aBindParams, $this->_aBindParams );
					}

					break;
				case "COMMAND" :
					$sSql = $this->_buildSelect ();
					$aBindParams = $this->_aBindParams;
					break;
				default :
					$select = $this->_buildSelect ();
					$from = $this->_buildFrom ();
					$where = $this->_buildWhere ();
					$join = $this->_buildJoin ();
					$order = $this->_buildOrder ();
					$group = $this->_buildGroupBy ();
					$having = $this->_buildHaving ();
					$limit = $this->_buildLimit ();
					$sSql .= $select . " FROM " . $from . " " . $join . " WHERE " . $where . $group . $having . $order . $limit;
					$aBindParams = $this->_aBindParams;
					break;
			}
			$this->_sSQL = $sSql;
			$this->_aBindParams = $aBindParams;
		}
		return array (
				$this->_sSQL,
				$this->_aBindParams
		);
	}
	public function rawSQL($sql, $bind_data = array())
	{
		$this->_sSQL = $sql;
		$this->_aBindParams = $bind_data;
		return $this;
	}
	public function limit($iPage, $iLimit)
	{
		$this->_aPager = array (
				'page' => $iPage,
				'limit' => $iLimit
		);
		return $this;
	}
	public function orWhere($param, $bind_values = array(), $operator = "=")
	{
		return $this->where ( $param, $bind_values, $operator, "OR" );
	}
	public function where($param, $bind_values = array(), $operator = "=", $cond = "AND")
	{
		if (count ( $this->_aConds ) == 0)
		{
			$cond = "";
		}
		$this->_aConds [] = array (
				'param' => $param,
				'bind' => $bind_values,
				'cond' => $cond,
				'operator' => $operator
		);

		return $this;
	}
	public function from($sTableName, $sAlias = "")
	{
		$this->_aMainFrom = array (
				'table' => $sTableName,
				'alias' => $sAlias
		);
		$this->_sTableName = $sTableName;
		return $this;
	}
	public function join($sTableName, $sCondition, $sAlias = "", $sType = "INNER")
	{
		$this->_aJoins [] = array (
				'table' => $sTableName,
				'cond' => $sCondition,
				'type' => $sType,
				'alias' => $sAlias
		);
		return $this;
	}
	public function select($select)
	{
		$this->_aSelects [] = $select;
		return $this;
	}
	public function order($sColumn, $sType = "DESC")
	{
		$this->_aOrders [] = array (
				'column' => $sColumn,
				'type' => $sType
		);
	}
	public function group($mColumn)
	{
		$this->_aGroupBys [] = $mColumn;
		return $this;
	}
	public function having($sHavingCondition)
	{
		$this->_aHavings [] = $sHavingCondition;
		return $this;
	}
	public function clean()
	{
		$this->_aConds = array ();
		$this->_aSelects = array ();
		$this->_aGroupBys = array ();
		$this->_aJoins = array ();
		$this->_aOrders = array ();
		$this->_aHavings = array ();
		$this->_bIsSubQuery = false;
		$this->_sSQL = "";
		$this->_aBindParams = array ();
		$this->_aPager = array ();
		return $this;
	}
	public function getSqlStatement()
	{
		return $this->_sSQL;
	}
	public function getRawSql()
	{
		if (empty ( $this->_sSQL ))
		{
			$this->build ();
		}
		if (! count ( $this->_aBindParams ))
		{
			return $this->_sSQL;
		}
		$aPatterns = array ();
		$aValues = array ();
		foreach ( $this->_aBindParams as $key => $aBindParam )
		{
			$aPatterns [] = '/[?]/';

			if (is_array ( $aBindParam ))
			{
				$aValues [$key] = implode ( ',', $aBindParam );
			} elseif (is_null ( $aBindParam ))
			{
				$aValues [$key] = 'NULL';
			} else
			{
				$aValues [$key] = $aBindParam;
			}
		}
		array_walk ( $aValues, create_function ( '&$v, $k', 'if (!is_numeric($v) && $v!="NULL") $v = "\'".$v."\'";' ) );
		$sSql = preg_replace ( $aPatterns, $aValues, $this->_sSQL, 1, $iCount );
		return $sSql;
	}
	protected function _buildHaving()
	{
		if (count ( $this->_aHavings ))
		{
			return " HAVING " . implode ( ",", $this->_aHavings );
		}
		return "";
	}
	protected function _buildGroupBy()
	{
		$order = "";
		if (count ( $this->_aGroupBys ))
		{
			$tmp = array ();
			foreach ( $this->_aGroupBys as $key => $sGroupName )
			{
				$tmp [] = $sGroupName;
			}
			$order = " GROUP BY " . implode ( ',', $tmp );
		}
		return $order;
	}
	protected function _buildOrder()
	{
		$order = "";
		if (count ( $this->_aOrders ))
		{
			$tmp = array ();
			foreach ( $this->_aOrders as $key => $aOrder )
			{
				$tmp [] = $aOrder ['column'] . " " . $aOrder ['type'];
			}
			$order = implode ( ',', $tmp );
		}
		if (! empty ( $order ))
		{
			$order = " ORDER BY " . $order;
		}
		return $order;
	}
	protected function _buildJoin()
	{
		$join = "";
		if (count ( $this->_aJoins ))
		{
			foreach ( $this->_aJoins as $key => $aJoin )
			{
				$join .= $aJoin ['type'] . " JOIN " . $aJoin ['table'];
				if (! empty ( $aJoin ['alias'] ))
				{
					$join .= " AS " . $aJoin ['alias'];
				}
				if (! empty ( $aJoin ['cond'] ))
				{
					$join .= " ON (" . $aJoin ['cond'] . " )";
				}
			}
		}
		return $join;
	}
	protected function _buildWhere()
	{
		$where = "";

		if (count ( $this->_aConds ))
		{
			foreach ( $this->_aConds as $key => $aCond )
			{
				$where .= $aCond ['cond'] . " ";
				$bHasSubQuery = false;
				if ($aCond ['bind'] instanceof \APP\Engine\Database\Query)
				{
					list ( $sSubSql, $aSubBindParams ) = $aCond ['bind']->build ();
					$bHasSubQuery = true;
				}
				$where .= $aCond ['param'] . " " . $aCond ['operator'] . " ";
				switch (trim ( strtoupper ( $aCond ['operator'] ) ))
				{
					case "NOT EXISTS" :
					case "EXISTS" :
						$where = $aCond ['cond'] . " " . $aCond ['operator'] . " ";
					case "NOT IN" :
					case "IN" :
						if ($bHasSubQuery)
						{
							foreach ( $aSubBindParams as $key => $value )
							{
								$this->_aBindParams ["sub_" . $key] = $value;
							}
							$where .= '(' . $sSubSql . ')';
						} else
						{
							if (! is_array ( $aCond ['bind'] ))
							{
								$aCond ['bind'] = array (
										'hashkey.' . count ( $this->_aBindParams ) => $aCond ['bind']
								);
							}
							$tmp = array ();
							foreach ( $aCond ['bind'] as $key => $value )
							{
								$tmp [] = "?";
								$this->_aBindParams [$key] = $value;
							}
							$where .= '(' . implode ( ',', $tmp ) . ')';
						}
						break;
					case "NOT BETWEEN" :
					case "BETWEEN" :
						$where .= " ? AND ?";
						foreach ( $aCond ['bind'] as $key => $value )
						{
							$this->_aBindParams [$key] = $value;
						}
						break;

					default :
						$where .= " ? ";
						$this->_aBindParams ['hashkey_' . count ( $this->_aBindParams )] = $aCond ['bind'];
						break;
				}
			}
		} else
		{
			$where = " 1 = 1 ";
		}
		return $where;
	}
	protected function _buildSelect()
	{
		$select = "";
		if (count ( $this->_aSelects ))
		{
			$select = implode ( '', $this->_aSelects );
		}

		return $select;
	}
	protected function _buildFrom()
	{
		$from = $this->_aMainFrom ['table'];
		$aNoAliasCommand = array (
				"INSERT",
				"DELETE",
				"UPDATE",
				"TRUNCATE",
				"DROP"
		);
		if (! empty ( $this->_aMainFrom ['alias'] ) && ! in_array ( $this->_sCommand, $aNoAliasCommand ))
		{
			$from .= " AS " . $this->_aMainFrom ['alias'];
		}
		return $from;
	}
	protected function _buildLimit()
	{
		$iPage = isset ( $this->_aPager ['page'] ) ? (int)$this->_aPager ['page'] : null;
		$iLimit = isset ( $this->_aPager ['limit'] ) ? (int)$this->_aPager ['limit'] : null;
		if ($iPage == null && $iLimit == null)
		{
			return "";
		}
		if ($iPage <= 1)
		{
			$iOffset = 0;
		} else
		{
			$iOffset = ($iPage - 1) * $iLimit;
		}
		return " LIMIT " . $iOffset . "," . $iLimit;
	}
}
