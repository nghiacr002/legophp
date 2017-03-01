<?php

namespace APP\Application\Module\Core\Model\DbTable;

class Setting extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "setting";
    protected $_mPrimaryKey = "setting_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('var_name'), array('module'), array('default_value')
        ),
    );
    protected $_sRowClass = "\\APP\\Application\\Module\Core\Model\DbTable\\DbRow\\Setting";

}
