<?php

namespace APP\Application\Module\Blog\Model\DbTable\DbRow;

use APP\Engine\Database\DbRow;
use APP\Application\Module\Blog\Model\Blog as BlogModel;
use App\Engine\Url;
use APP\Engine\Image;
use APP\Engine\Database\Query;

class Blog extends DbRow
{

    public function href()
    {
        return (new Url())->makeUrl('blog_view_detail', array('slug' => $this->slug, 'id' => $this->blog_id));
    }

    public function blog_status_text()
    {
        $sText = "core.unknown";
        switch ($this->blog_status)
        {
            case BlogModel::STATUS_ACTIVATED:
                $sText = "core.activated";
                break;
            case BlogModel::STATUS_DRAFT:
                $sText = "core.draft";
                break;
            case BlogModel::STATUS_DEACTIVATED:
                $sText = "core.deactivated";
                break;
        }
        return $this->app()->language->translate($sText);
    }

    public function cover_image_url()
    {
        return (new Image())->getThumbUrl("Blog/" . $this->cover_image, 'small-size');
    }
    public function delete()
    {
    	$mResult = parent::delete();
    	$oQuery = new Query();
		$oQuery->setCommand("Delete");
		$oQuery->from(\APP\Engine\Database\DbTable::getFullTableName('meta_tags'));
		$oQuery->where('item_type','blog');
		$oQuery->where('item_id',$this->blog_id);
		$oQuery->execute();
    	return $mResult;
    }
}
