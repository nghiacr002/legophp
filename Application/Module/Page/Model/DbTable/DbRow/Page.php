<?php

namespace APP\Application\Module\Page\Model\DbTable\DbRow;

use APP\Engine\Database\DbRow;
use APP\Application\Module\Page\Model\Page as PageModel;
use App\Engine\Url;
use APP\Engine\Database\Query;

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
	public function delete()
	{
		$mResult = parent::delete();
		//clean
		$oQuery = new Query();
		$oQuery->setCommand("Delete");
		$oQuery->from(\APP\Engine\Database\DbTable::getFullTableName('layout_widgets'));
		$oQuery->where('item_type','page');
		$oQuery->where('item_id',$this->page_id);
		$oQuery->execute();

		$oQuery = new Query();
		$oQuery->setCommand("Delete");
		$oQuery->from(\APP\Engine\Database\DbTable::getFullTableName('meta_tags'));
		$oQuery->where('item_type','page');
		$oQuery->where('item_id',$this->page_id);
		$oQuery->execute();

		return $mResult;
	}
}
