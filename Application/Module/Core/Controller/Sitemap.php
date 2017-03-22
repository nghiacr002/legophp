<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Controller;
use APP\Library\Sitemap;
class SitemapController extends Controller
{
    public function IndexAction()
    {

    }
    public function BuildAction()
    {
    	$mResults = $this->helper->callback('buildSitemap',array('day' => date('Y-m-d',APP_TIME),'group_item' => true));
    	$oSiteMapXML = new Sitemap();
    	$oSiteMapXML->start('index.xml','sitemapindex');
    	foreach($mResults as $iKey => $mResult)
    	{
    		if(!isset($mResult['loc']))
    		{
    			foreach($mResult as $iKey2 => $mItem)
    			{
    				$oSiteMapXML->addItem($mItem);
    			}
    		}
    		else
    		{
    			$oSiteMapXML->addItem($mResult);
    		}

    	}
    	$oSiteMapXML->end();
    	$oSiteMapXML->write();
    	exit;
    }
}
