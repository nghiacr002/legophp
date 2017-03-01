<?php

namespace APP\Application\Module\Page\Model\DbTable;

class Page extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "page";
    protected $_mPrimaryKey = "page_id";
    protected $_sAlias = "page";
    protected $_aValidateRules = array(
        'required' => array(
            array('page_title'), array('page_url')
        ),
    );
    protected $_sRowClass = "\\APP\\Application\\Module\Page\Model\DbTable\\DbRow\\Page";

}
