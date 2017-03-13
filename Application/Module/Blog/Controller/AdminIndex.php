<?php

namespace APP\Application\Module\Blog;

use APP\Engine\Module\Controller;
use APP\Application\Module\Blog\Model\Blog as BlogModel;
use APP\Engine\HTML\Filter;
use APP\Application\Module\Core\Model\Category;
use APP\Application\Module\Blog\Form\BlogItem;
use APP\Engine\Image;
use APP\Engine\HTML\Pagination;
use APP\Application\Module\Core\Model\HashTag;
use APP\Application\Module\Core\Model\MetaTag;

class AdminIndexController extends Controller
{

    public function IndexAction()
    {
        $this->url()->redirect('blog/manage', array('admincp' => true));
    }

    public function DeleteAction()
    {
        $this->auth()->acl()->hasPerm('blog.can_delete_blog', true);
        $oBlogModel = new BlogModel();
        $iId = $this->request()->get('id');

        $this->view->oExistedBlog = $oExistedBlog = $oBlogModel->getOne($iId);

        if (!$oExistedBlog || !$oExistedBlog->blog_id)
        {
            $this->app->flash->set($this->language()->translate('blog.blog_does_not_exist'));
            $this->url()->redirect('blog/manage', array('admincp' => true));
        }
        if ($oExistedBlog->delete())
        {
            $this->app->flash->set($this->language()->translate('blog.deleted_blog_successfully'));
            $this->url()->redirect('blog/manage', array('admincp' => true));
        } else
        {
            throw new AppException($this->language()->translate('blog.cannot_delete_this_blog'), HTTP_CODE_BAD_REQUEST);
        }
    }

    public function EditAction()
    {
        $this->auth()->acl()->hasPerm('blog.can_edit_blog', true);
        $oBlogModel = new BlogModel();
        $iId = $this->request()->get('id');
        $this->view->oExistedBlog = $oExistedBlog = $oBlogModel->getOne($iId);
        if (!$oExistedBlog || !$oExistedBlog->blog_id)
        {
            $this->app->flash->set($this->language()->translate('blog.blog_does_not_exist'));
            $this->url()->redirect('blog/manage', array('admincp' => true));
        }
        $oFormBlogItem = new BlogItem();
        $this->view->oFormBlogItem = $oFormBlogItem;
        if ($this->request()->isPost())
        {
            if ($oFormBlogItem->isValid())
            {
                try
                {
                    $aData = $oFormBlogItem->getFormValues();
                    $sHashTag = "";
                    if (isset($aData['hashtag']))
                    {
                        $sHashTag = $aData['hashtag'];
                        unset($aData['hashtag']);
                    }
                    if (empty($aData['cover_image']))
                    {
                        $aData['cover_image'] = $oExistedBlog->cover_image;
                    }

                    $aData['blog_id'] = $oExistedBlog->blog_id;

                    $oExistedBlog->setData($aData);
                    if ($oExistedBlog->isValid())
                    {
                        if ($oExistedBlog->update())
                        {
                            $this->app->flash->set($this->language()->translate('blog.updated_blog_successfully'));
                        }
                        //update hashtag
                        (new HashTag())->updateHash($sHashTag, $oExistedBlog->blog_id, "blog");
                        //working with meta tags
                        $aMetaTags = $this->request()->get('meta');
                        (new MetaTag())->updateMetags($aMetaTags, "blog", $oExistedBlog->blog_id);
                        $this->url()->redirect('blog/edit', array('id' => $iId, 'admincp' => true));
                    } else
                    {
                        $aErrors = $oExistedBlog->getErrors();
                        $this->flash()->set($aErrors, "system", 'error');
                    }
                } catch (AppException $ex)
                {
                    Logger::error($ex);
                    $this->flash()->set($this->language()->translate('core.there_are_some_problems_with_system_please_try_again'), "system", 'error');
                }
            } else
            {
                $aMessages = $oFormBlogItem->getMessages();
                foreach ($aMessages as $sId => $sMessage)
                {
                    $this->flash()->set($sMessage, $sId, 'error');
                }
            }
        } else
        {
            $aValues = $oExistedBlog->getProps();
            $aValues['hashtag'] = (new HashTag())->getByItem($oExistedBlog->blog_id, "blog");
            $oFormBlogItem->setFormValues($aValues);
        }
        $this->template()->setHeader(array(
            'jquery-ui.min.js' => 'module_core',
            'jquery-ui.min.css' => 'module_core',
            'jquery.filemanager.js' => 'module_core',
            'jquery.filemanager.css' => 'module_core',
            'tinymce/tinymce.min.js' => 'module_core',
            'admin_blog.js' => 'module_blog',
            'admin_blog.css' => 'module_blog',
        ));

        $sUrl = $this->url()->makeUrl('blog/manage', array('admincp' => true));
        $aBreadCrumb = array(
            'title' => $this->language()->translate('blog.blog'),
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $sUrl => $this->language()->translate('blog.manage'),
            ),
        );

        $this->view->sFormUrl = $this->url()->makeUrl('blog/edit', array('id' => $iId, 'admincp' => true));
        $this->template()->setBreadCrumb($aBreadCrumb);
        $this->view->setLayout('Add.tpl');
        $this->setActiveMenu('blog/manage/index');
    }

    public function AddAction()
    {
        $this->auth()->acl()->hasPerm('blog.can_add_blog', true);
        $oFormBlogItem = new BlogItem();
        $this->view->oFormBlogItem = $oFormBlogItem;
        if ($this->request()->isPost())
        {
            if ($oFormBlogItem->isValid())
            {
                try
                {
                    $aData = $oFormBlogItem->getFormValues();
                    $sHashTag = "";
                    if (isset($aData['hashtag']))
                    {
                        $sHashTag = $aData['hashtag'];
                        unset($aData['hashtag']);
                    }
                    $oBlogModel = new BlogModel();
                    $oNewBlog = $oBlogModel->getTable()->createRow($aData);
                    $oNewBlog->created_time = APP_TIME;
                    $oNewBlog->owner_id = $this->auth()->getViewer()->user_id;
                    if ($oNewBlog->isValid())
                    {
                        $iId = $oNewBlog->save();
                        if ($iId)
                        {
                            if ($sHashTag)
                            {
                                (new HashTag())->insertHash($sHashTag, $iId, "blog");
                            }
                            ////working with meta tags
                            $aMetaTags = $this->request()->get('meta');
                            (new MetaTag())->updateMetags($aMetaTags, "blog", $iId);

                            $this->app->flash->set($this->language()->translate('blog.added_new_blog_successfully'));
                            $this->url()->redirect('blog/edit', array('id' => $iId, 'admincp' => true));
                        }
                    } else
                    {
                        $aErrors = $oNewUser->getErrors();
                        $this->flash()->set($aErrors, "system", 'error');
                    }
                } catch (AppException $ex)
                {
                    Logger::error($ex);
                    $this->flash()->set($this->language()->translate('core.there_are_some_problems_with_system_please_try_again'), "system", 'error');
                }
            } else
            {
                $aMessages = $oFormBlogItem->getMessages();
                foreach ($aMessages as $sId => $sMessage)
                {
                    $this->flash()->set($sMessage, $sId, 'error');
                }
            }
        }
        $this->template()->setHeader(array(
            'jquery-ui.min.js' => 'module_core',
            'jquery-ui.min.css' => 'module_core',
            'jquery.filemanager.js' => 'module_core',
            'jquery.filemanager.css' => 'module_core',
            'tinymce/tinymce.min.js' => 'module_core',
            'admin_blog.js' => 'module_blog',
            'admin_blog.css' => 'module_blog',
        ));

        $sUrl = $this->url()->makeUrl('blog/manage', array('admincp' => true));
        $aBreadCrumb = array(
            'title' => $this->language()->translate('blog.blog'),
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $sUrl => $this->language()->translate('blog.manage'),
            ),
        );
        $this->view->sFormUrl = $this->url()->makeUrl('blog/add', array('admincp' => true));
        $this->template()->setBreadCrumb($aBreadCrumb);
    }

    public function ManageAction()
    {
        $this->auth()->acl()->hasPerm('blog.can_access_blog', true);
        $oBlogModel = (new BlogModel());
        $iLimit = $this->request()->get('limit', 20);
        $iCurrentPage = $this->request()->get('page', 1);
        $this->view->oFilter = $oFilter = new Filter();
        $aCategories = (new Category())->getCategoriesByType("blog");
        $aOptions = array();
        $aOptions[] = array(
            'value' => '',
            'name' => $this->language()->translate('core.all')
        );
        foreach ($aCategories as $ikey => $oCategory)
        {
            $aOptions[] = array(
                'name' => $oCategory['category_name'],
                'value' => $oCategory['category_id']
            );
            if (isset($oCategory['sub']) && count($oCategory['sub']))
            {
                foreach ($oCategory['sub'] as $iKey2 => $aSubCategory)
                {
                    $aOptions[] = array(
                        'name' => $oCategory['category_name'] . ' / ' . $aSubCategory['category_name'],
                        'value' => $aSubCategory['category_id']
                    );
                }
            }
        }

        $oFilter->setParams(array(
            'full-name' => array(
                'type' => 'text',
                'name' => 'blog_title',
                'placeholder' => $this->language()->translate('blog.enter_keyword_to_search')
            ),
            'category' => array(
                'type' => 'select',
                'name' => 'blog.category_id',
                'options' => $aOptions
            ),
            'search-button' => array(
                'type' => 'search',
                'class' => 'btn btn-success',
                'value' => $this->language()->translate('core.search')
            )
        ));
        $aFilter = $oFilter->getFilterValues();
        $aConds = array();
        if (isset($aFilter) && count($aFilter))
        {
            foreach ($aFilter as $iKey => $mValue)
            {
                if ($iKey == "blog.category_id")
                {
                    $aSubCategories = isset($aCategories[$mValue]) ? $aCategories[$mValue]['sub'] : array();
                    if (count($aSubCategories))
                    {
                        $aSub = array();
                        foreach ($aSubCategories as $iKeySubCat => $aSubCat)
                        {
                            $aSub[] = $aSubCat['category_id'];
                        }
                        $aConds[] = array($iKey, $aSub, 'IN');
                    } else
                    {
                        $aConds[] = array($iKey, '%' . $mValue . '%', 'LIKE');
                    }
                } else
                {
                    $aConds[] = array($iKey, '%' . $mValue . '%', 'LIKE');
                }
            }
        }

        $iTotal = $oBlogModel->getTotal($aConds);
        $aBlogs = $oBlogModel->getAll($aConds, $iCurrentPage, $iLimit, 'blog.*,user.full_name,category.category_name', array('created_time', 'DESC'));
        $this->view->iTotal = $iTotal;
        $this->view->aBlogs = $aBlogs;
        $sUrl = $this->url()->makeUrl('blog/manage', array('admincp' => true));
        $aBreadCrumb = array(
            'title' => $this->language()->translate('blog.blog'),
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $sUrl => $this->language()->translate('blog.blog'),
            ),
        );
        $this->template()->setBreadCrumb($aBreadCrumb);
        $aParams = array(
            'router' => 'blog/manage',
            'params' => array('admincp' => true, 'limit' => $iLimit),
            'total' => $iTotal,
            'current' => $iCurrentPage,
            'limit' => $iLimit,
        );
        if (count($aFilter))
        {
            $aParams['params'] = array_merge($aParams['params'], $aFilter);
        }
        $this->view->paginator = new Pagination($aParams);
    }

}
