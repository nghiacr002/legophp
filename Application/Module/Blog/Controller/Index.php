<?php
namespace APP\Application\Module\Blog;
use APP\Engine\Module\Controller;
use APP\Application\Module\Theme\Model\Layout;
use APP\Application\Module\Blog\Model\Blog as BlogModel;
use APP\Engine\HTML\Filter;
use APP\Engine\HTML\Pagination;
class IndexController extends Controller
{
    public function IndexAction()
    {
    	$iLimit = $this->request()->get('limit', 5);
    	$iCurrentPage = $this->request()->get('page', 1);
    	$this->view->oFilter = $oFilter = new Filter();
    	$oFilter->setParams(array(
    			'blog-title' => array(
    					'type' => 'text',
    					'name' => 'blog_title',
    					'placeholder' => $this->language()->translate('blog.enter_keyword_to_search')
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
    	$oBlogModel = new BlogModel();
    	$iTotal = $oBlogModel->getTotal($aConds);
    	$aBlogs = $oBlogModel->getAll($aConds, $iCurrentPage, $iLimit, 'blog.*,user.full_name,category.category_name', array('created_time', 'DESC'));
    	$this->view->iTotal = $iTotal;
    	$this->view->aBlogs = $aBlogs;
    	$sUrl = $this->url()->makeUrl('blog');
    	$aParams = array(
    			'router' => 'blog',
    			'params' => array( 'limit' => $iLimit),
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
    public function ViewAction()
    {
    	$iId = $this->request()->get('id');
    	$this->view->oBlogItem = $oBlog = (new Blog())->getOne($iId);
    	if(!$oBlog || !$oBlog->blog_id)
    	{
    		$this->url()->redirect('blog',array(),$this->language()->translate('core.item_not_found'),'error');
    	}
    	if($oBlog->layout_id > 0)
    	{
    		$oLayoutModel = (new Layout());
    		$oLayout = $oLayoutModel->getOne($oBlog->layout_id);
    		if($oLayout && $oLayout->layout_id)
    		{
    			$oLayout->item_id = $iId;
    			$oLayout->item_type = "blog";
    			$this->template()->setLayout($oLayout);
    			$this->template()->inLegoMode(true);
    		}
    	}

    }
}
