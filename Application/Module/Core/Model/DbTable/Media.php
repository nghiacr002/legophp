<?php

namespace APP\Application\Module\Core\Model\DbTable;

class Media extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "media";
    protected $_mPrimaryKey = "media_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('media_title'), array('destination')
        ),
    );
    protected $_sRowClass = "\\APP\\Application\\Module\Core\Model\DbTable\\DbRow\\Media";

}
