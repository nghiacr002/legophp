<?php

namespace APP\Application\Module\Core\Model\DbTable\DbRow;

use APP\Engine\Database\DbRow;

class MetaTag extends DbRow
{
    public function toString()
    {
    	switch($this->meta_group)
    	{
    		case 'default':
    			return sprintf('<meta name="%s" content="%s"/>',$this->meta_tag,$this->meta_content);
    		case 'open_graph':
    			return sprintf('<meta property="%s" content="%s"/>',$this->meta_tag,$this->meta_content);
    	}
    	return "";
    }
}
