<?php
namespace APP\Application\Module\User\Model\DbTable\DbRow;
class RequestToken extends \APP\Engine\Database\DbRow
{
	const STATUS_CREATED = 0;
	const STATUS_PROCESSED = 1;
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
}
