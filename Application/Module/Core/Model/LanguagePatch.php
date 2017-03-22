<?php
namespace APP\Application\Module\Core\Model;
use APP\Engine\Module\Model;
use APP\Application\Module\Core\Model\DbTable\LanguagePatch as DbLanguagePatch;
class LanguagePatch extends Model
{
	public function __construct()
	{
		$this->_oTable = new DbLanguagePatch();
		parent::__construct();
	}
 	public function getAllWithCache($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "*", $mOrder = null)
    {
        $sCacheName = $this->_oTable->getTableName();
        $sCacheName = $sCacheName . md5($sSelectFields . $mOrder . $iPage . $iLimit);

        if ($aRows = $this->cache()->get($sCacheName,"Model"))
        {
            return $aRows;
        }
        $aRows = parent::getAll($aConds, $iPage, $iLimit, $sSelectFields, $mOrder);
        $aResult = array();
        if (count($aRows))
        {
            foreach ($aRows as $iKey => $aRow)
            {
                $aRow = $aRow->toArray();
                $aResult[$aRow['language_code']][$aRow['var_name']] = $aRow['value'];
            }
        }
        if ($aResult)
        {
            $this->cache()->set($sCacheName, $aResult, $this->getTTL(), "Model");
        }
        return $aResult;
    }
}
