<?php

namespace APP\Application\Module\Blog\Form;

use APP\Engine\HTML\Form as HtmlForm;
use APP\Engine\HTML\Input;
use APP\Engine\HTML\Select;
use APP\Engine\HTML\Textarea;
use APP\Application\Module\Core\Model\Category;
use APP\Application\Module\Blog\Model\Blog;
use APP\Engine\File as FileManager;
use APP\Engine\HTML\File as File;
use APP\Application\Module\Theme\Model\Layout;

class BlogItem extends HtmlForm
{

    public function __construct()
    {
        parent::__construct();
        $this->_aElements = array();
        $aPostData = $this->_aData;

        $oInput = new Select();
        $oInput->setName("category_id");
        $aCategories = (new Category())->getCategoriesByType("blog");

        foreach ($aCategories as $ikey => $aCategory)
        {
            $aSubCats = array();
            if (count($aCategory['sub']))
            {
                foreach ($aCategory['sub'] as $iKey => $aSubCat)
                {
                    $aSubCats[$aSubCat['category_id']] = $aSubCat['category_name'];
                }
            }
            $oInput->setOption($aCategory['category_id'], $aCategory['category_name'], $aSubCats);
        }
        $this->addElement($oInput);

        $oInput = new Input();
        $oInput->setName("blog_title");
        $oInput->required(true);
        $oInput->setErrorMessage($this->app->language->translate('blog.title_cannot_be_empty'));
        $this->addElement($oInput);

        $oInput = new Input();
        $oInput->setName("slug");
        $oInput->required(true);
        $oInput->setErrorMessage($this->app->language->translate('blog.slug_cannot_be_empty'));
        $this->addElement($oInput);

        $oInput = new Textarea();
        $oInput->setName('sort_description');
        $oInput->setValue($this->request()->get('sort_description'));
        $oInput->required(true);
        $oInput->setErrorMessage($this->app->language->translate('blog.sort_description_cannot_be_empty'));
        $this->addElement($oInput);

        $oInput = new Textarea();
        $oInput->setName('blog_description');
        $oInput->setValue($this->request()->get('blog_description'));
        $this->addElement($oInput);

        $oInput = new File();
        $oInput->setName('cover_image');
        $oInput->setFileType(array(
            "png", "jpg", "gif", "jpeg"
        ));
        $this->addElement($oInput);
        if ($this->request()->isPost())
        {
            if ($_FILES && $oInput->validateFileType())
            {

                $oFile = new FileManager();
                $sUploadedFile = $oFile->upload($oInput->getName(), APP_UPLOAD_PATH . "Blog" . APP_DS);
                if ($sUploadedFile)
                {
                    $oInput->setValue($sUploadedFile);
                } else
                {
                    $aFileInfo = $oFile->getFileInfo();
                    $sMessage = $oFile->getErrorFile($aFileInfo['error']);
                    if ($sMessage)
                    {
                        $oInput->setErrorMessage($sMessage)->setMessage($sMessage);
                    }
                }
            }
            else
            {
            	$oInput->forceValid(true);
                $oInput->setValue("");
                $oInput->setMessage("");
            }
        }
        $oInput = new Input();
        $oInput->setName("hashtag");
        $this->addElement($oInput);

        $oInput = new Select();
        $oInput->setName("blog_status");
        $oInput->required(true);
        $oInput->setOption(Blog::STATUS_ACTIVATED, $this->language()->translate('blog.activated'));
        $oInput->setOption(Blog::STATUS_DRAFT, $this->language()->translate('blog.draft'));
        $oInput->setOption(Blog::STATUS_DEACTIVATED, $this->language()->translate('blog.deactivated'));
        $this->addElement($oInput);

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

    }

}
