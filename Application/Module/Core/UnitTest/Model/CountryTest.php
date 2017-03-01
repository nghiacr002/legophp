<?php

use PHPUnit\Framework\TestCase;
use APP\Application\Module\Core\Model\Country;
use \APP\Application\Module\Core\Model\DbTable\Country as DbCountry;

class CountryTest extends TestCase
{

    const NUMBER_OF_COUNTRY = 232;

    public function testGetAllCountries()
    {
        $aCountries = (new Country())->getAll(array());
        $this->assertCount(self::NUMBER_OF_COUNTRY, $aCountries);
    }

    public function testInsertCountry()
    {
        $aData = array(
            'country_code' => "ABC",
            'country_name' => "Nice",
            'country_phone' => "+99"
        );
        $oNewCountry = (new DbCountry())->createRow($aData);
        $this->assertEquals(true, $oNewCountry->isValid());
    }

    /**
     * @dataProvider listTestCountry 
     */
    public function testGetOneCountry($sIso, $sName)
    {
        $oCountry = (new Country())->getOne($sIso, 'country_code');
        $this->assertEquals($sName, ($oCountry) ? $oCountry->country_name : null);
    }

    public function listTestCountry()
    {
        return array(
            array("VN", "Vietnam"),
            array("Vn", "Vietnam"),
            array("vn", "Vietnam"),
            array("v'n", "Vietnam"),
            array("'v n", "Vietnam"),
        );
    }

}
