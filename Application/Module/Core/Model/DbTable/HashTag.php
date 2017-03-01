<?php

namespace APP\Application\Module\Core\Model\DbTable;

class HashTag extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "hashtag";
    protected $_mPrimaryKey = "hashtag_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('hashtag_name'), array('hashtag_code')
        ),
    );
    protected $_sRowClass = "\\APP\\Application\\Module\Core\Model\DbTable\\DbRow\\HashTag";

}
