<?php

namespace APP\Application\Module\User\Model\DbTable;

class Group extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "user_group";
    protected $_mPrimaryKey = "user_group_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('group_name')
        ),
    );
    protected $_sRowClass = "\\APP\\Application\\Module\User\Model\DbTable\\DbRow\\Group";

    public function businessValidate(\APP\Engine\Database\DbRow $oGroup)
    {
        return true;
    }

}
