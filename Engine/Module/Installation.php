<?php
namespace APP\Engine\Module;

use APP\Engine\Object;

class Installation extends Object
{
	protected $_sCurrentInstallVersion = null;
	public function setInstallVersion($sVersion)
	{
		$this->_sCurrentInstallVersion = $sVersion;
		return $this;
	}
	public function process()
	{
		return false;
	}
}