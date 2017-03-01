<?php

namespace APP\Application\Module\Core\Model\DbTable;

class Note extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "note";
    protected $_mPrimaryKey = "note_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('note_title'), array('note_description')
        ),
    );
    protected $_sRowClass = "\\APP\\Application\\Module\Core\Model\DbTable\\DbRow\\Note";

}
