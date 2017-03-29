<?php

namespace APP\Application\Module\Page\Form;

use APP\Engine\HTML\Form as HtmlForm;
use APP\Engine\HTML\Input;
use APP\Engine\HTML\Select;
use APP\Engine\HTML\Textarea;
use APP\Application\Module\Page\Model\Page;
use APP\Application\Module\Theme\Model\Layout;

class PageItem extends HtmlForm
{

    public function __construct()
    {
        parent::__construct();
        $this->_aElements = array();
        $aPostData = $this->_aData;

        $oInput = new Select();
        $oInput->setName("page_layout");
        $aPageLayouts = (new Layout())->getAll();
        //$oInput->setOption(-1, $this->language()->translate('page.default_layout'));
        if (count($aPageLayouts))
        {
            foreach ($aPageLayouts as $aLayout)
            {
                $oInput->setOption($aLayout->layout_id, $aLayout->layout_title);
            }
        }

        $this->addElement($oInput);


        $oInput = new Input();
        $oInput->setName("page_title");
        $oInput->required(true);
        $oInput->setErrorMessage($this->app()->language->translate('page.page_cannot_be_empty'));
        $this->addElement($oInput);

        $oInput = new Input();
        $oInput->setName("page_url");
        $oInput->required(true);
        $oInput->setErrorMessage($this->app()->language->translate('page.url_cannot_be_empty'));
        $this->addElement($oInput);


        $oInput = new Textarea();
        $oInput->setName('page_content');
        $oInput->setValue($this->request()->get('page_content'));
        $this->addElement($oInput);

        $oInput = new Textarea();
        $oInput->setName('custom_css');
        $oInput->setValue($this->request()->get('custom_css'));
        $this->addElement($oInput);

        $oInput = new Textarea();
        $oInput->setName('custom_js');
        $oInput->setValue($this->request()->get('custom_js'));
        $this->addElement($oInput);

        $oInput = new Input();
        $oInput->setName("hashtag");
        $this->addElement($oInput);

        $oInput = new Select();
        $oInput->setName("page_status");
        $oInput->required(true);
        $oInput->setOption(Page::STATUS_ACTIVATED, $this->language()->translate('page.activated'));
        $oInput->setOption(Page::STATUS_DRAFT, $this->language()->translate('page.draft'));
        $oInput->setOption(Page::STATUS_DEACTIVATED, $this->language()->translate('page.deactivated'));
        $this->addElement($oInput);
    }

}
