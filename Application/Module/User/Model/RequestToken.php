<?php
namespace APP\Application\Module\User\Model;
use APP\Engine\Module\Model;
use APP\Application\Module\User\Model\DbTable\RequestToken as DbRequestToken;
class RequestToken extends Model
{

	public function __construct()
	{
		$this->_oTable = new DbRequestToken();
		parent::__construct();
	}
}
