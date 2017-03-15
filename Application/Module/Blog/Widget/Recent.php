<?php

namespace APP\Application\Module\Blog;

use APP\Engine\Module\Widget;
use APP\Application\Module\Blog\Model\Blog;

class RecentWidget extends Widget
{
    public function process()
    {
    	$aParams = $this->getParams();
    	$iLimit = isset($aParams['limit']) ? (int)$aParams['limit'] : 5;
    	$aConds = array(
    		array(
    			'blog.blog_status', Blog::STATUS_ACTIVATED
    		)
    	);
    	$aBlogItems = (new Blog())->getAll($aConds, 0,$iLimit,"*",array('created_time','DESC'));
    	if(!count($aBlogItems))
    	{
    		return false;
    	}
    	$this->view->aBlogItems = $aBlogItems;
    	$sTitle = isset($aParams['title']) ? $aParams['title']: $this->language()->get('blog.recent_blogs');
    	$this->setTitle($sTitle);
    }

}
