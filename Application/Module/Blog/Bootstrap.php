<?php

namespace APP\Application\Module\Blog;

use APP\Engine\Module\Bootstrap as ModuleBootstrap;
use APP\Application\Module\Blog\Model\Blog as BlogModel;
use APP\Library\Sitemap;

class Bootstrap extends ModuleBootstrap
{

    protected function initTemplate()
    {

    }

    public function getAdminMenu()
    {
        //$sAdminUrlPath = $this->app->getConfig('system','admin_path');
        $aMenu = array(
            'name' => $this->app->language->translate('blog.blog'),
            'url' => '#',
            'icon' => 'fa fa-globe',
            'sub' => array(
                'blog/category/index' => array(
                    'name' => $this->app->language->translate('core.manage_category'),
                    'url' => $this->app->router->url()->makeUrl('core/category', array('type' => 'blog', 'admincp' => true)),
                    'icon' => '',
                ),
                'blog/manage/index' => array(
                    'name' => $this->app->language->translate('blog.manage_blog'),
                    'url' => $this->app->router->url()->makeUrl('blog/manage', array('admincp' => true)),
                    'icon' => '',
                ),
                'blog/add/index' => array(
                    'name' => $this->app->language->translate('blog.add_new'),
                    'url' => $this->app->router->url()->makeUrl('blog/add', array('admincp' => true)),
                    'icon' => '',
                ),
            ),
        );
        return $aMenu;
    }

    public function getStatistic()
    {
        $iTotalBlog = (new BlogModel())->getTotal();
        return array(
            'blog' => array(
                'text' => $this->app->language->translate('blog.total_blog'),
                'value' => $iTotalBlog,
            )
        );
    }
    protected function subscribeEvent()
    {
    	$this->app()->event->subscribe("onDeleteBlog",array((new BlogModel()),'onDeleteBlog'));
    }
    public function buildSitemap($aParams = array())
    {
    	//build static content only
    	$oSiteMapXML = new Sitemap();
    	$oURL = $this->app->router->url();
    	$sDate = isset($aParams['day']) ? $aParams['day'] : date('Y-m-d');
    	$oBlogModel = (new BlogModel());
    	$iPage = 1;
    	$iLimit = 1000;
    	$aConds = array(
    		array('blog_status',BlogModel::STATUS_ACTIVATED)
    	);
    	$aSiteIndexMap = array();
		do
		{
			$aBlogs = $oBlogModel->getAll($aConds,$iPage,$iLimit);
			if(is_array($aBlogs) && count($aBlogs))
			{
				$sBlogSiteMapName = 'blog-'.$iPage.'.xml';
				$oSiteMapXML->start($sBlogSiteMapName);
				$aSiteIndexMap[] = array(
			    		'loc' => $oURL->makeUrl('Public/Sitemap/' . $sBlogSiteMapName),
			    		'lastmod' => $sDate,
			    	);
				foreach($aBlogs as $oBlog)
				{
					$oSiteMapXML->addItem(array(
						'loc' => $oBlog->href(),
						'lastmod' => $sDate,
					));
				}
				$oSiteMapXML->end();
				$oSiteMapXML->write();
				$iPage++;
			}
			else
			{
				break;
			}

		}while(1==1);
    	/**
    	 * Should return index map sitemap
    	 */
    	return $aSiteIndexMap;
    }
}
