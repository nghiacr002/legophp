<?php

namespace APP\Application\Module\Core\Model\DbTable;

class Language extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "language";
    protected $_mPrimaryKey = "language_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('language_name'), array('language_code')
        ),
    );
    protected $_sRowClass = "\\APP\\Application\\Module\Core\Model\DbTable\\DbRow\\Language";

}
