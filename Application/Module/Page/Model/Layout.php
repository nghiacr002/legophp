<?php 
namespace APP\Application\Module\Page\Model;
use APP\Application\Module\Page\Model\DbTable\Layout as DbLayout;
use APP\Engine\Module\Model;
class Layout extends Model
{
	public function __construct()
	{
		$this->_oTable = new DbLayout();
		parent::__construct();
	}
	
}
