<?php

namespace APP\Application\Module\Core\Model;

use APP\Application\Module\Core\Model\DbTable\MetaTag as DbMetaTag;
use APP\Engine\Module\Model;

class MetaTag extends Model
{
    private $_aMetaTags = array(
        'default' => array(
            'keyword' => '',
            'title' => '',
            'robots' => '',
            'description' => '',
        ),
        'open_graph' => array(
            'og:title' => '',
            'og:url' => '',
            'og:type' => '',
            'og:description' => '',
            'og:image' => '',
        ),
    );

    public function __construct()
    {
        $this->_oTable = new DbMetaTag();
        parent::__construct();
    }

    public function updateMetags($aMeta = array(), $sType = "", $iItemId = 0)
    {
        $this->deleteByItem($sType, $iItemId);
        foreach ($aMeta as $sGroup => $aData)
        {
            foreach ($aData as $sMetaName => $sContent)
            {
                $aInsert = array(
                    'meta_tag' => $sMetaName,
                    'meta_content' => $sContent,
                    'meta_group' => $sGroup,
                    'item_id' => $iItemId,
                    'item_type' => $sType,
                );
                $oRowMetaTag = $this->getTable()->createRow($aInsert);
                try
                {
                    $oRowMetaTag->save();
                } catch (\AppException $ex)
                {
                    //do nothing
                }
            }
        }
    }

    public function getSupportMetaTags()
    {
        $aMeta = array();
        return $this->_aMetaTags;
    }

    public function deleteByItem($sType, $iItemId)
    {
        $oQuery = $this->getTable()->createQuery("delete");
        $oQuery->where('item_id', $iItemId);
        $oQuery->where('item_type', $sType);
        return $oQuery->execute();
    }

    public function getByItem($iItemId, $sType, $bIsViewMode = false)
    {
        if ($iItemId !== null)
        {
            $aConds[] = array(
                'item_id', $iItemId,
            );
        }
        if ($sType)
        {
            $aConds[] = array(
                'item_type', $sType,
            );
        }

        $aRows = $this->getAll($aConds);
        if($bIsViewMode == true)
        {
        	$aDefaults = array();
        }
        else
        {
        	$aDefaults = $this->getSupportMetaTags();
        }
        if (count($aRows))
        {
        	if($bIsViewMode == true)
        	{
        		foreach ($aRows as $iKey => $oRow)
        		{
        			$aDefaults[] = $oRow->toString();
        		}
        	}
            else
            {
	            foreach ($aRows as $iKey => $oRow)
	            {
	                $aDefaults[$oRow->meta_group][$oRow->meta_tag] = $oRow->meta_content;
	            }
            }
        }
        return $aDefaults;
    }

    public function getAll($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "*", $mOrder = null)
    {
        $sCacheName = $this->_oTable->getTableName();
        $sCacheName = $sCacheName . md5($sSelectFields . $mOrder . $iPage . $iLimit);
        if ($aRows = $this->cache()->get($sCacheName))
        {
            return $aRows;
        }
        $aRows = parent::getAll($aConds, $iPage, $iLimit, $sSelectFields, $mOrder);

        if ($aRows)
        {
            $this->cache()->set($sCacheName, $aRows, 100, "Model");
        }
        return $aRows;
    }

}
