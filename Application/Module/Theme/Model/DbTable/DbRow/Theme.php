<?php

namespace APP\Application\Module\Theme\Model\DbTable\DbRow;

use APP\Engine\Database\DbRow;
use App\Engine\Url;
use APP\Application\Module\Theme\Model\Layout;

class Theme extends DbRow
{
	private $_oDefaultLayout = null;
    public function getLogoPath()
    {
    	if(empty($this->logo)){
    		return null;
    	}
    	return (new Url())->makeUrl('image/origin',array('path' => $this->logo));
    }
    public function getDefaultLayout()
    {
    	if(!$this->_oDefaultLayout)
    	{
    		$this->_oDefaultLayout = (new Layout())->getOne(1,'is_default_template');
    	}
    	return $this->_oDefaultLayout;
    }
}
