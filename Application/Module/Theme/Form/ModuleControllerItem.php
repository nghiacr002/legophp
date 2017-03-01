<?php

namespace APP\Application\Module\Theme\Form;

use APP\Engine\HTML\Form as HtmlForm;
use APP\Engine\HTML\Select;
use APP\Engine\HTML\Textarea;
use APP\Application\Module\Theme\Model\Layout;
class ModuleControllerItem extends HtmlForm
{

    public function __construct()
    {
        parent::__construct();
        $this->_aElements = array();
        $aPostData = $this->_aData;

        $oInput = new Select();
        $oInput->setName("layout_id");
        $aPageLayouts = (new Layout())->getAll();
        $oInput->setOption(0, $this->language()->translate('page.default_layout'));
        if (count($aPageLayouts))
        {
            foreach ($aPageLayouts as $aLayout)
            {
                $oInput->setOption($aLayout->layout_id, $aLayout->layout_title);
            }
        }
        $this->addElement($oInput);
        
        $oInput = new Textarea();
        $oInput->setName('custom_css');
        $oInput->setValue($this->request()->get('custom_css'));
        $this->addElement($oInput);

        $oInput = new Textarea();
        $oInput->setName('custom_js');
        $oInput->setValue($this->request()->get('custom_js'));
        $this->addElement($oInput);
    }

}
