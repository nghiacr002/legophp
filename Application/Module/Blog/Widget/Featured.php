<?php

namespace APP\Application\Module\Blog;

use APP\Engine\Module\Widget;
use APP\Application\Module\Blog\Model\Blog;

class FeaturedWidget extends Widget
{
    public function process()
    {
    	$aParams = $this->getParams();
    	$iLimit = isset($aParams['limit']) ? (int)$aParams['limit'] : 5;
    	$aBlogItems = (new Blog())->getFeaturedItems($iLimit);
    	if(!count($aBlogItems))
    	{
    		return false;
    	}
    	$this->view->aBlogItems = $aBlogItems;
    	$sTitle = isset($aParams['title']) ? $aParams['title']: $this->language()->get('blog.featured_blogs');
    	$this->setTitle($sTitle);
    }

}
