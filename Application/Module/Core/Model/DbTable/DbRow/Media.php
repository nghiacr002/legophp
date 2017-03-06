<?php

namespace APP\Application\Module\Core\Model\DbTable\DbRow;

use APP\Engine\Database\DbRow;

class Media extends DbRow
{

    public function display()
    {
        return "";
    }

    public function getEmbedCode()
    {
        return "";
    }
	public function beforeSave()
	{
		$aMeta = isset($this->_aData['meta_file']) ? $this->_aData['meta_file']: array();
		$this->_aData['meta_file'] = serialize($aMeta);
		return $this;
	}
}
