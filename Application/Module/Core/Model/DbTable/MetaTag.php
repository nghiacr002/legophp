<?php

namespace APP\Application\Module\Core\Model\DbTable;

class MetaTag extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "meta_tags";
    protected $_mPrimaryKey = "meta_tag_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('meta_tag'), array('meta_content')
        ),
    );
    protected $_sRowClass = "\\APP\\Application\\Module\Core\Model\DbTable\\DbRow\\MetaTag";

}
