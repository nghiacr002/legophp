<?php

namespace APP\Application\Module\Theme\Model\DbTable;

class Layout extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "layout";
    protected $_mPrimaryKey = "layout_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('layout_title'), array('layout_name')
        )
    );
    protected $_sRowClass = "\APP\Application\Module\Theme\Model\DbTable\DbRow\Layout";

}
