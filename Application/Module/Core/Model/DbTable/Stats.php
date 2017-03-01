<?php

namespace APP\Application\Module\Core\Model\DbTable;

class Stats extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "system_stats";
    protected $_mPrimaryKey = "stats_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('stats_type'), array('stats_value'), array('module_name'), array('last_update')
        )
    );
    protected $_sRowClass = "\APP\Application\Module\Core\Model\DbTable\DbRow\Stats";

}
