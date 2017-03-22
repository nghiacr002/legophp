<?php

namespace APP\Application\Module\Page\Model;

use APP\Application\Module\Page\Model\DbTable\Page as DbPage;
use APP\Engine\Module\Model;
use APP\Engine\Database\Query;

class Page extends Model
{

    const STATUS_ACTIVATED = 1;
    const STATUS_DRAFT = -2;
    const STATUS_DEACTIVATED = -1;

    protected $_iTimeToLive = 1000;

    public function __construct()
    {
        $this->_oTable = new DbPage();
        parent::__construct();
    }

    public function getByUrl($sUrl)
    {
        $sCacheName = "page-" . $sUrl;
        if ($aRow = $this->cache()->get($sCacheName,"Model"))
        {
            return $aRow;
        }
        $aRow = $this->getOne($sUrl, "page_url");
        if ($aRow)
        {
            $this->cache()->set($sCacheName, $aRow, $this->getTTL(), "Model");
        }
        return $aRow;
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
            foreach ($aRows as $iKey => $aPage)
            {
                $aRow = $aPage->toArray();
                $aResult[$aRow['page_url']] = $aRow;
            }
        }
        if ($aResult)
        {
            $this->cache()->set($sCacheName, $aResult, $this->getTTL(), "Model");
        }
        return $aResult;
    }
    public function setLandingPage($iPageId)
    {
    	$oQuery = new Query("update");
    	$oQuery->setTableData($this->getTable()->getTableName(), array(
    		'is_landing_page' => 0,
    	));
    	$bResult1 = $this->getTable()->executeQuery($oQuery);
    	$oQuery->clean();
    	$oQuery->where('page_id', $iPageId);
    	$oQuery->setTableData($this->getTable()->getTableName(), array(
    			'is_landing_page' => 1,
    	));
    	$bResult2 = $this->getTable()->executeQuery($oQuery);
    	$this->cache()->remove('page-landing-page');
    	return $bResult2;
    }
	public function getLandingPage()
	{
		$sCacheName = "page-landing-page";
		if ($aRow = $this->cache()->get($sCacheName,"Model"))
		{
			return $aRow;
		}
		$aRow =  $this->getOne(1, 'is_landing_page');
		if ($aRow)
		{
			$this->cache()->set($sCacheName, $aRow, $this->getTTL(), "Model");
		}
		return $aRow;

	}
}
