<?php
namespace APP\Application\Module\Blog;
use APP\Engine\Module\Controller;
use APP\Application\Module\Theme\Model\Layout;
use APP\Application\Module\Blog\Model\Blog;
class IndexController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
    public function IndexAction()
    {
        
    }
    public function ViewAction()
    {
    	$iId = $this->request()->get('id');
    	$this->view->oBlogItem = $oBlog = (new Blog())->getOne($iId);
    	if(!$oBlog || !$oBlog->blog_id)
    	{
    		$this->url()->redirect('blog',array(),$this->language()->translate('core.item_not_found'),'error');
    	}
    	if($oBlog->layout_id > 0)
    	{
    		$oLayoutModel = (new Layout());
    		$oLayout = $oLayoutModel->getOne($oBlog->layout_id);
    		if($oLayout && $oLayout->layout_id)
    		{
    			$oLayout->item_id = $iId;
    			$oLayout->item_type = "blog";
    			$this->template()->setLayout($oLayout);
    			$this->template()->inLegoMode(true);
    		}
    	}
    	
    }
}
