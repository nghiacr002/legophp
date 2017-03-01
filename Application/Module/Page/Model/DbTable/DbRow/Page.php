<?php

namespace APP\Application\Module\Page\Model\DbTable\DbRow;

use APP\Engine\Database\DbRow;
use APP\Application\Module\Page\Model\Page as PageModel;
use App\Engine\Url;

class Page extends DbRow
{

    public function href()
    {
        return (new Url())->makeUrl('page_view_detail', array('slug' => $this->page_url, 'id' => $this->page_id));
    }

    public function page_status_text()
    {
        $sText = "core.unknown";
        switch ($this->page_status)
        {
            case PageModel::STATUS_ACTIVATED:
                $sText = "core.activated";
                break;
            case PageModel::STATUS_DRAFT:
                $sText = "core.draft";
                break;
            case PageModel::STATUS_DEACTIVATED:
                $sText = "core.deactivated";
                break;
        }
        return $this->app()->language->translate($sText);
    }

}
