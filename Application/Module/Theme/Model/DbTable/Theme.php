<?php

namespace APP\Application\Module\Theme\Model\DbTable;

class Theme extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "theme";
    protected $_mPrimaryKey = "theme_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('theme_title'), array('folder')
        ),
    );
    protected $_sRowClass = "\\APP\\Application\\Module\Theme\Model\DbTable\\DbRow\\Theme";

}
