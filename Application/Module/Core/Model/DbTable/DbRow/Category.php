<?php

namespace APP\Application\Module\Core\Model\DbTable\DbRow;

use APP\Engine\Database\DbRow;
use App\Engine\Url;

class Category extends DbRow
{
    public function href()
    {
    	$sURL = '#';
    	if($this->category_id > 0){
    		$sURIName = strtolower($this->category_type.'_category_detail');
    		$sSlug = \APP\Engine\Utils::getSlug($this->category_name);
    		$sURL = (new Url())->makeUrl($sURIName,array('id' => $this->category_id,'slug' => $sSlug));
    	}
		return $sURL;
    }
}
