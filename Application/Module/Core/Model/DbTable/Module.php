<?php

namespace APP\Application\Module\Core\Model\DbTable;

class Module extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "module";
    protected $_mPrimaryKey = "module_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('module_name'), array('module_title')
        ),
    );
    protected $_sRowClass = "\\APP\\Application\\Module\Core\Model\DbTable\\DbRow\\Module";

}
