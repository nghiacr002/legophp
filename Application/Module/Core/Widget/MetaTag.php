<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Widget;
use APP\Application\Module\Core\Model\MetaTag as MetagModel;

class MetaTagWidget extends Widget
{

    public function __construct()
    {
        parent::__construct();
    }

    public function process()
    {
        $sType = $this->type;
        $iItemId = (int)$this->id;

        $oMetaTagModel = (new MetagModel());
        $aMetaTags = $oMetaTagModel->getByItem($iItemId, $sType);

        $this->view->aDefaultMetaTags = $aMetaTags;
    }

}
