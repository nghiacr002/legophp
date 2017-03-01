<?php 
namespace APP\Application\Module\Theme\Model\DbTable\DbRow;
class LayoutDesign extends \APP\Engine\Database\DbRow
{
	public function __get($sName)
	{
		if($sName == "param_values" && isset($this->_aData['param_values']) )
		{
			if(is_string($this->_aData['param_values']))
			{
				return @json_decode($this->_aData['param_values'],true);
			}
		}
		return parent::__get($sName);
	}
	public function beforeSave()
	{
		$this->format();
	}
	public function beforeUpdate()
	{
		$this->format();
	}
	public function format()
	{
		if(isset($this->_aData['param_values']) && is_array($this->_aData['param_values']) )
		{
			$this->_aData['param_values'] = @json_encode($this->_aData['param_values']);
		}
	}
	public function isDefaultDesign()
	{
		return true;
	}
}
