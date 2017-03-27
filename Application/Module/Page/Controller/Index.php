<?php

namespace APP\Application\Module\Page;

use APP\Application\Module\Core\Model\MetaTag;
use APP\Application\Module\Theme\Model\Layout;
use APP\Engine\Module\Controller;
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
        $this->view->aStaticPage = $aStaticPage = $this->request()->get('aStaticPage');
        $sPageUrl = isset($aStaticPage['page_url']) ? $aStaticPage['page_url'] : "";
        if (!empty($sPageUrl))
        {
            $sAction = str_replace('-', '', $sPageUrl);
            $sActionName = ucfirst($sAction) . "Action";
            if (method_exists($this, $sActionName))
            {
                $this->view->setLayout(ucfirst($sAction));
                return $this->{$sActionName}();
            }
        }
        if(isset($aStaticPage['page_layout'])  && $aStaticPage['page_layout'] > 0)
        {
        	$oLayoutModel = (new Layout());
        	$oLayout = $oLayoutModel->getOne($aStaticPage['page_layout']);
        	if($oLayout && $oLayout->layout_id)
        	{
        		$oLayout->item_id = $aStaticPage['page_id'];
        		$oLayout->item_type = "page";

        		$oLayout->header = (int) !$aStaticPage['hide_header_layout'];
        		$oLayout->footer = (int) !$aStaticPage['hide_footer_layout'];
        		$oLayout->custom_css = $aStaticPage['custom_css'];
        		$oLayout->custom_js = $aStaticPage['custom_js'];
        		$this->template()->setLayout($oLayout);
        		$this->template()->inLegoMode(true);
        	}
        }
        $this->template()->setTitle($aStaticPage['page_title']);
        $aMetaTags = (new MetaTag())->getByItem($aStaticPage['page_id'], "page",true);
        if(count($aMetaTags))
        {
        	foreach($aMetaTags as $sTag => $sTagContent)
        	{
        		$this->template()->setMeta($sTagContent,$sTag);
        	}
        }
        //$this->template()->setCustomMetaTags($aMetaTags);
    }

    public function ContactusAction()
    {
        //custom code here
    }
}
