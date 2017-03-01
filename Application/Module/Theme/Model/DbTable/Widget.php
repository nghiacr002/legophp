<?php

namespace APP\Application\Module\Theme\Model\DbTable;

class Widget extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "widgets";
    protected $_mPrimaryKey = "widget_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('widget_name'), array('widget_router'), array('module_name')
        )
    );
    protected $_sRowClass = "\APP\Application\Module\Theme\Model\DbTable\DbRow\Widget";

}
