<?php

namespace App\Engine;

use Valitron\Validator as Valitron;

class Validator extends Valitron
{

    public function __construct($data, $fields = array(), $lang = null, $langDir = null)
    {
        parent::__construct($data, $fields, $lang, $langDir);
        //self::$_ruleMessages['email'] = 
    }

}
