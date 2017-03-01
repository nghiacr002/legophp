<?php

namespace APP\Application\Module\Core\Model\DbTable\DbRow;

use APP\Engine\Database\DbRow;

class Module extends DbRow
{
	public function exporter()
	{
		return $this->_factory ( "Exporter" );
	}
	public function installation()
	{
		return $this->_factory("Installation");
	}

	protected function _factory($sType = "Exporter")
	{
		$sModule = isset ( $this->_aData ['module_name'] ) ? ucfirst ( $this->_aData ['module_name'] ) : "";

		$oObject = null;
		if (! empty ( $sModule ))
		{
			try
			{
				$oObject = \APP\Engine\Module\Component::factory ( $sModule, $sType, "" );
			} catch ( \Exception $ex )
			{
			}
		}

		return $oObject;
	}
}
