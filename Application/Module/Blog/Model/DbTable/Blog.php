<?php

namespace APP\Application\Module\Blog\Model\DbTable;

class Blog extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "blog";
    protected $_mPrimaryKey = "blog_id";
    protected $_sAlias = "blog";
    protected $_aValidateRules = array(
        'required' => array(
            array('blog_title'), array('slug'), array('sort_description')
        ),
    );
    protected $_sRowClass = "\\APP\\Application\\Module\Blog\Model\DbTable\\DbRow\\Blog";

}
