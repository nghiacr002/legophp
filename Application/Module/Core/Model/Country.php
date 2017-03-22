<?php

namespace APP\Application\Module\Core\Model;

use APP\Application\Module\Core\Model\DbTable\Country as DbCountry;
use APP\Engine\Application;
use APP\Engine\Module\Model;

class Country extends Model
{

    public function __construct()
    {
        $this->_oTable = new DbCountry();
        parent::__construct();
    }

    public function getAll($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "*", $mOrder = null)
    {
        $sCacheName = $this->_oTable->getTableName();
        $sCacheName = $sCacheName . md5($sSelectFields . $mOrder . $iPage . $iLimit);
        if ($oCountries = $this->cache()->get($sCacheName,"Model"))
        {
            return $oCountries;
        }
        $oCountries = parent::getAll($aConds, $iPage, $iLimit, $sSelectFields, $mOrder);
        if ($oCountries)
        {
            $this->cache()->set($sCacheName, $oCountries, $this->getTTL(), "Model");
        }
        return $oCountries;
    }

}
