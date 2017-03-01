<?php
namespace APP\Application\Module\Theme\Model\DbTable;

class LayoutWidgets extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "layout_widgets";
    protected $_mPrimaryKey = "pw_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('item_id'), array('item_type'), array('location_id')
        )
    );
    protected $_sRowClass = "\APP\Application\Module\Theme\Model\DbTable\DbRow\LayoutWidgets";

}
