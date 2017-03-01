<?php

namespace APP\Application\Module\User\Model\DbTable;

class GroupPermission extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "user_group_permission";
    protected $_mPrimaryKey = "group_permission_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('permission_id'), array('user_group_id')
        ),
    );

    //protected $_sRowClass = "\\APP\\Application\\Module\User\Model\DbTable\\DbRow\\Permission";
}
