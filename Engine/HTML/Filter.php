<?php

namespace APP\Engine\HTML;

class Filter extends Element
{

    public function __construct($aParams = array())
    {
        parent::__construct();
        $this->setParams($aParams);
    }

    protected $_aParams = array();

    /**
     * 
     * @param array $aParams
     * @return \APP\Engine\HTML\Filter
     * Example: array(
     * 	'id-1' => array(
     * 		'type' => 'text',
     * 		'class' => '',
     * 		'name' => '',
     * 		'placeholer' => '',
     *   ),
     *   'id-2' => array(
     *   	'type' => 'select'
     *   	'class' => '',
     *  	'name' => '', 
     *  	'value' => '', 
     *  	'options' => array(
     *  		'name' => '',
     *  		'value' => '',
     *  	)
     *   )
     * )
     */
    public function setParams($aParams = array())
    {
        $this->_aParams = $aParams;
        return $this;
    }

    public function getFilterValues()
    {
        $aReturn = array();
        $aPostData = \APP\Engine\Application::getInstance()->request->get('filter');
        foreach ($this->_aParams as $Type => $aData)
        {
            $sName = isset($aData['name']) ? $aData['name'] : "";
            if (empty($sName))
            {
                continue;
            }
            $sValue = isset($aPostData[$sName]) ? $aPostData[$sName] : "";
            if (!empty($sValue))
            {
                $aReturn[$sName] = $sValue;
            }
        }
        return $aReturn;
    }

    public function render()
    {
        $sHTML = "";
        $aPostData = \APP\Engine\Application::getInstance()->request->get('filter');
        foreach ($this->_aParams as $sId => $aData)
        {
            if (!isset($aData['class']))
            {
                $aData['class'] = 'form-control filter-control';
            }
            $sName = isset($aData['name']) ? $aData['name'] : "";
            $sValue = isset($aPostData[$sName]) ? $aPostData[$sName] : "";
            $sType = isset($aData['type']) ? $aData['type'] : "";
            switch ($sType)
            {
                case 'label':
                    $sText = '<label>%s<label>';
                    $sText = sprintf($sText, $aData['value']);
                    break;
                case 'text':
                    $sText = '<input type="text" class="filter-element %s" placeholder="%s" value="%s" id="filter-%s" name="filter[%s]"/>';
                    $sText = sprintf($sText, $aData['class'], isset($aData['placeholder']) ? $aData['placeholder'] : "", $sValue, $sId, $sName);
                    break;
                case 'select':
                    $sText = '<select id="filter-%s" name="filter[%s]" class="filter-element %s">';
                    $sText = sprintf($sText, $sId, $sName, $aData['class']);
                    if (isset($aData['options']) && count($aData['options']))
                    {
                        foreach ($aData['options'] as $iKey => $mData)
                        {
                            $sSelected = "";
                            if ($mData['value'] == $sValue)
                            {
                                $sSelected = 'selected';
                            }
                            $sText .= '<option value="%s" %s >%s</option>';
                            $sText = sprintf($sText, $mData['value'], $sSelected, $mData['name']);
                        }
                    }
                    $sText .= '</select>';
                    break;
                case 'search':
                    $sClass = isset($aData['class']) ? $aData['class'] : "btn btn-success";
                    $sValue = isset($aData['value']) ? $aData['value'] : " ";
                    $sText = '<input type="submit" class="%s" value="%s" id="search-submit-button"/>';
                    $sText = sprintf($sText, $sClass, $sValue);
                    break;
            }
            $sHTML .= $sText;
        }

        $sHTML = '<div id="filter-holder" class="filter-holder">' . $sHTML . '</div>';
        return $this->rawHTML($sHTML);
    }

}
