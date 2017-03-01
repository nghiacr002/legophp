<?php

namespace APP\Application\Module\Core\Model;

use APP\Application\Module\Core\Model\DbTable\Media as DbMedia;
use APP\Engine\Module\Model;
use App\Engine\Url;

class Media extends Model
{

    protected $_oRow;

    public function __construct($mValue = "")
    {
        $this->_oTable = new DbMedia();
        parent::__construct();
    }

    public function getOriginalUrl($sPath)
    {
        return (new Url())->makeUrl('media/original/' . urlencode($sPath));
    }

    public function cast($mValue)
    {
        $oMediaItem = null;
        if (!empty($mValue))
        {
            $sType = strtolower(gettype($mValue));
            switch ($sType)
            {
                case 'string':
                    $sColumn = "destination";
                    break;
                case 'int':
                default:
                    $sColumn = "media_id";
                    break;
            }
            $oMediaItem = $this->getOne($mValue, $sColumn);
            if (!$oMediaItem && $sType == "string")
            {
                $oMediaItem = $this->getTable()->createRow(array(
                    'destination' => $mValue
                ));
                $oMediaItem = system_cast_object($oMediaItem, $this->getTable()->getRowClass());
            }
        }

        return $oMediaItem;
    }

}
