<?php

namespace APP\Application\Module\Theme\Model\DbTable\DbRow;

use APP\Engine\Database\DbRow;

class Widget extends \APP\Engine\Database\DbRow
{
	public function widget_name_translated()
	{
		return $this->app ()->language->translate ( $this->widget_name );
	}
	public function beforeSave()
	{
		if (is_array ( $this->params ))
		{
			$this->params = json_encode ( $this->params );
		}
	}
	public function beforeUpdate()
	{
		if (is_array ( $this->params ))
		{
			$this->params = json_encode ( $this->params );
		}
	}
	public function __get($sName)
	{
		if ($sName == "params")
		{
			if (is_string ( $this->_aData ['params'] ))
			{
				return @json_decode ( $this->_aData ['params'], true );
			}
		}
		return parent::__get ( $sName );
	}
}
