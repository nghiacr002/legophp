<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Controller;
use APP\Application\Module\Page\Model\Page as PageModel;
class IndexController extends Controller
{
    public function IndexAction()
    {
        $oLandingPage = (new PageModel())->getLandingPage();
        if($oLandingPage && $oLandingPage->page_id)
        {
			$this->router()->forward(array(
				'module' => 'page',
				'controller' => 'index',
				'action' => 'view'
			),array(
				'aStaticPage' => $oLandingPage->toArray()
			));
        }
    }
}
