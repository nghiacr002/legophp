<?php
namespace APP\Application\Module\User\Model\DbTable\DbRow;
class RequestToken extends \APP\Engine\Database\DbRow
{
	const STATUS_CREATED = 0;
	const STATUS_PROCESSED = 1;
	public function beforeSave()
	{
		if (is_array ( $this->_aData['params'] ))
		{
			$this->_aData['params'] = json_encode ( $this->_aData['params'] );
		}
	}
	public function beforeUpdate()
	{
		if (is_array ( $this->_aData['params'] ))
		{
			$this->_aData['params'] = json_encode ( $this->_aData['params'] );
		}
	}
	public function __get($sName)
	{
		if($sName == "params")
		{
			if(isset($this->_aData['params']) && !empty($this->_aData['params']))
			{
				$this->_aData['params']= json_decode($this->_aData['params'],true);
			}
		}
		return parent::__get($sName);
	}
}
