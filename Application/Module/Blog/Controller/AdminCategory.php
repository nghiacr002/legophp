<?php

namespace APP\Application\Module\Blog;

use APP\Engine\Module\Controller;
use APP\Application\Module\Blog\Model\Category as ModelCategory;

class AdminCategoryController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function IndexAction()
    {
        $oModelCategory = (new ModelCategory());
        $aCategories = $oModelCategory->getAll();
        $this->template()->setHeader(array(
            'bootstrap-switch.min.js' => 'module_core',
            'bootstrap-switch.min.css' => 'module_core',
            'admin_category.js' => 'module_blog'
        ));
        $this->view->aCategories = $aCategories;
        $aBreadCrumb = array(
            'title' => $this->language()->translate('core.manage_category'),
            'extra_title' => '',
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $this->url()->makeUrl('blog/category', array('admincp' => true)) => $this->language()->translate('core.manage_category'),
            ),
        );
        $this->template()->setBreadCrumb($aBreadCrumb);
    }

}
