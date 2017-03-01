<?php

namespace APP\Engine\HTML;

class Pagination extends Element
{

    public function __construct($aParams = array())
    {
        parent::__construct();
        $this->setParams($aParams);
    }

    protected $_aParams = array();

    /**
     * set Paging information
     * @param array $aParams
     * Example: array(
     * 	  'router' => 'user/browse',
     * 	  'params' => array('p1' => 'v1', 'p2' => 'v2' )
     * 	  'total' => 10, 
     * 	  'limit' => 2,
     * 	  'current' => 3,
     * 	  
     * )
     */
    public function setParams($aParams = array())
    {
        $this->_aParams = $aParams;
        return $this;
    }

    public function render()
    {
        $aParams = $this->getParams();
        $iPage = $aParams['current'];
        $iLimit = $aParams['limit'];
        $iLastPage = ceil($aParams['total'] / $iLimit);
        $sPagingURL = $aParams['router'];
        $aPagingParams = $aParams['params'];
        $iNext = $iPage + 1;
        $iPrev = $iPage - 1;
        $iPrevLast = $iLastPage - 1;
        $iAdjacents = 3;
        $sPageHTML = "";
        $oURL = $this->app->router->url();
        //d($sPagingURL);
        if ($iLastPage > 1)
        {
            $sPageHTML .= '<ul class="pagination pagination-sm no-margin">';
            if ($iPage > 1)
            {
                $sPageHTML .= '<li><a href="' . $oURL->makeURL($sPagingURL, array_merge($aPagingParams, array('page' => $iPrev))) . '">«</a></li>';
            }
            if ($iLastPage < 7 + ($iAdjacents * 2))    //not enough pages to bother breaking it up
            {
                for ($iCounter = 1; $iCounter <= $iLastPage; $iCounter++)
                {
                    if ($iCounter == $iPage)
                        $sPageHTML .= "<li class='active'><a class=\"current\" href=\"#\">$iCounter</a></li>";
                    else
                        $sPageHTML .= "<li><a href=\"" . $oURL->makeURL($sPagingURL, array_merge($aPagingParams, array('page' => $iCounter))) . "\">$iCounter</a></li>";
                }
            }
            elseif ($iLastPage > 5 + ($iAdjacents * 2))
            {
                if ($iPage < 1 + ($iAdjacents * 2))
                {
                    for ($iCounter = 1; $iCounter < 4 + ($iAdjacents * 2); $iCounter++)
                    {
                        if ($iCounter == $iPage)
                        {
                            $sPageHTML .= "<li class='active'><a class=\"current\" href=\"#\">$iCounter</a></li>";
                        } else
                        {
                            $sPageHTML .= "<li><a href=\"" . $oURL->makeURL($sPagingURL, array_merge($aPagingParams, array('page' => $iCounter))) . "\">$iCounter</a></li>";
                        }
                    }
                    $sPageHTML .= "<li><a href=\"#\">...</a></li>";
                    $sPageHTML .= "<li><a href=\"" . $oURL->makeURL($sPagingURL, array_merge($aPagingParams, array('page' => $iPrevLast))) . "\">$iPrevLast</a></li>";
                    $sPageHTML .= "<li><a href=\"" . $oURL->makeURL($sPagingURL, array_merge($aPagingParams, array('page' => $iLastPage))) . "\">$iLastPage</a></li>";
                } elseif ($iLastPage - ($iAdjacents * 2) > $iPage && $iPage > ($iAdjacents * 2))
                {
                    $sPageHTML .= "<li><a href=\"" . $oURL->makeURL($sPagingURL, array_merge($aPagingParams, array('page' => 1))) . "\">1</a></li>";
                    $sPageHTML .= "<li><a href=\"" . $oURL->makeURL($sPagingURL, array_merge($aPagingParams, array('page' => 2))) . "\">2</a></li>";
                    $sPageHTML .= "<li><a href=\"#\">...</a></li>";
                    for ($iCounter = $iPage - $iAdjacents; $iCounter <= $iPage + $iAdjacents; $iCounter++)
                    {
                        if ($iCounter == $iPage)
                        {
                            $sPageHTML .= "<li class='active'><a class=\"current\" href=\"#\">$iCounter</a></li>";
                        } else
                        {
                            $sPageHTML .= "<li><a href=\"" . $oURL->makeURL($sPagingURL, array_merge($aPagingParams, array('page' => $iCounter))) . "\">$iCounter</a></li>";
                        }
                    }
                    $sPageHTML .= "<li><a href=\"#\">...</a></li>";
                    $sPageHTML .= "<li><a href=\"" . $oURL->makeURL($sPagingURL, array_merge($aPagingParams, array('page' => $iPrevLast))) . "\">$iPrevLast</a></li>";
                    $sPageHTML .= "<li><a href=\"" . $oURL->makeURL($sPagingURL, array_merge($aPagingParams, array('page' => $iLastPage))) . "\">$iLastPage</a></li>";
                }
                //close to end; only hide early pages
                else
                {
                    $sPageHTML .= "<li><a href=\"" . $oURL->makeURL($sPagingURL, array_merge($aPagingParams, array('page' => 1))) . "\">1</a></li>";
                    $sPageHTML .= "<li><a href=\"" . $oURL->makeURL($sPagingURL, array_merge($aPagingParams, array('page' => 2))) . "\">2</a></li>";
                    $sPageHTML .= "<li><a href=\"#\">...</a></li>";
                    for ($iCounter = $iLastPage - (2 + ($iAdjacents * 2)); $iCounter <= $iLastPage; $iCounter++)
                    {
                        if ($iCounter == $iPage)
                        {
                            $sPageHTML .= "<li class='active'><a class=\"current\" href=\"#\">$iCounter</a></li>";
                        } else
                        {
                            $sPageHTML .= "<li><a href=\"" . $oURL->makeURL($sPagingURL, array_merge($aPagingParams, array('page' => $iCounter))) . "\">$iCounter</a></li>";
                        }
                    }
                }
            }

            if ($iPage < $iCounter - 1)
            {
                $sPageHTML .= '<li><a href="' . $oURL->makeURL($sPagingURL, array_merge($aPagingParams, array('page' => $iNext))) . '">»</a></li>';
            }
            $sPageHTML .= '</ul>';
        }
        return $this->rawHTML($sPageHTML);
    }

    protected function getParams()
    {
        return $this->_aParams;
    }

}
