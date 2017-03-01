<?php

namespace APP\Application\Module\Core\Model\DbTable;

class Category extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "category";
    protected $_mPrimaryKey = "category_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('category_name'), array('category_type')
        ),
    );
    protected $_sRowClass = "\\APP\\Application\\Module\Core\Model\DbTable\\DbRow\\Category";

}
