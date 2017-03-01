<?php

namespace APP\Engine;

include_once APP_PATH_LIB . 'PHPImageWorkshop' . APP_DS . 'ImageWorkshop.php';

use PHPImageWorkshop\ImageWorkshop;
use App\Engine\Url;

class Image extends ImageWorkshop
{

    protected $_aDefaultSizes = array(
        'medium' => '200',
        'small' => '100',
        'large' => '400',
    );

    public function getDefaultSize($sSize = "")
    {
        return isset($this->_aDefaultSizes[$sSize]) ? $this->_aDefaultSizes[$sSize] : "";
    }

    public function getThumbUrl($sPath, $sSize = "medium-square")
    {
        $sPath = str_replace(APP_UPLOAD_PATH, "", $sPath);
        return (new Url())->makeUrl('image/' . $sSize, array('path' => $sPath));
    }

    public function displayContent($sFileName, $sContentType = null)
    {
        return (new File())->displayContent($sFileName, $sContentType);
    }

}
