<?php

namespace APP\Application\Module\Theme\Model\DbTable;

class Menu extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "menu";
    protected $_mPrimaryKey = "menu_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('menu_name'), array('url')
        ),
    );
    protected $_sRowClass = "\\APP\\Application\\Module\Theme\Model\DbTable\\DbRow\\Menu";

}
