<?php

namespace APP\Application\Module\Core\Model\DbTable;

class Country extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "country";
    protected $_mPrimaryKey = "country_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('country_code'), array('country_name')
        ),
    );
    protected $_sRowClass = "\\APP\\Application\\Module\Core\Model\DbTable\\DbRow\\Country";

}
