<?php

namespace APP\Application\Module\Blog;

use APP\Engine\Module\Widget;
use APP\Application\Module\Blog\Model\Blog;

class FeaturedWidget extends Widget
{
    public function process()
    {
    	$iLimit = 5;
    	$aBlogItems = (new Blog())->getFeaturedItems($iLimit);
    	if(!count($aBlogItems))
    	{
    		return false;
    	}
    	$this->view->aBlogItems = $aBlogItems;
    	$this->setTitle($this->language()->get('blog.featured_blogs'));
    }

}
