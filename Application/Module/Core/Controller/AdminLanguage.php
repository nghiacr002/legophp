<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Controller;
use APP\Application\Module\Core\Model\Language as ModelLanguage;
use APP\Application\Module\Core\Model\DbTable\DbRow\Language;
use APP\Engine\HTML\Filter;
use APP\Application\Module\Core\Model\LanguagePatch as LanguagePatchModel;

class AdminLanguageController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth()->acl()->hasPerm('core.can_access_language_page', true);
        $aMenus = array(
        	'customize' => array(
        		'title' => $this->language()->translate('core.customize'),
        		'action' => $this->url()->makeUrl('core/language/customize',array('admincp' => true)),
        		'custom' => '',
        		'class' => 'btn btn-warning'
        	),
        	'add-phrase' => array(
        		'title' => $this->language()->translate('core.add_phrase'),
        		'action' => $this->url()->makeUrl('core/language/add',array('admincp' => true)),
        		'custom' => '',
        		'class' => 'btn btn-warning'
        	)
        		
        );
        $this->app()->setSharedData('custom-menu-header',$aMenus);
    }

    public function IndexAction()
    {
        $oModelLanguage = (new ModelLanguage());
        $aLanguages = $oModelLanguage->getAll();
        $this->template()->setHeader(array(
            'bootstrap-switch.min.js' => 'module_core',
            'bootstrap-switch.min.css' => 'module_core',
            'admin_language.js' => 'module_core'
        ));
        $this->view->aLanguages = $aLanguages;
        $aBreadCrumb = array(
            'title' => $this->language()->translate('core.languages'),
            'extra_title' => '',
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $this->url()->makeUrl('core/language', array('admincp' => true)) => $this->language()->translate('core.language'),
            ),
        );
        $this->template()->setBreadCrumb($aBreadCrumb);
        
    }

    public function UpdateAllAction()
    {
        $oModelLanguage = (new ModelLanguage());
        $aVals = $this->request()->getParams(true);
        if (isset($aVals['is_default']) && $aVals['is_default'] > 0)
        {
            $oModelLanguage->setDefault($aVals['is_default']);
            if (isset($aVals['is_active']) && count($aVals['is_active']))
            {
                foreach ($aVals['is_active'] as $sValue)
                {
                    $aParts = explode('-', $sValue);
                    if (count($aParts) == 2)
                    {
                        if ($aParts[1] == 0 && $aParts[0] == $aVals['is_default'])
                        {
                            continue;
                        }
                        try
                        {
                            $oLanguage = $oModelLanguage->getOne($aParts[0]);
                            if ($oLanguage->language_id)
                            {
                                $oLanguage->is_active = $aParts[1];
                                $oLanguage->update();
                            }
                        } catch (Exception $ex)
                        {
                            
                        }
                    }
                }
            }
        }
    }
    public function CustomizeAction()
    {
    	$oModelLanguage = (new ModelLanguage());
    	$aLanguages = $oModelLanguage->getAll();
    	$this->view->oFilter = $oFilter = new Filter();
    	$aOptions = array();
    	$aOptions[] = array(
    			'value' => '',
    			'name' => $this->language()->translate('core.all')
    	);
    	foreach($aLanguages as $iKey => $oLanguage)
    	{
    		$aOptions[] =array(
    				'value' => $oLanguage->language_code,
    				'name' => $oLanguage->language_name
    		);
    	}
    	$oFilter->setParams(array(
    			'phrase-var' => array(
    					'type' => 'text',
    					'name' => 'phrase_var',
    					'placeholder' => $this->language()->translate('blog.enter_keyword_to_search')
    			),
    			'category' => array(
    					'type' => 'select',
    					'name' => 'language',
    					'options' => $aOptions
    			),
    			'limit-display' => array(
    					'type' => 'select',
    					'name' => 'limit',
    					'options' => array(
    							array(
    									'value' => 10,
    									'name' => 10,
    							),
    							array(
    									'value' => 20,
    									'name' => 20,
    							)
    					)
    			),
    			'search-button' => array(
    					'type' => 'search',
    					'class' => 'btn btn-success',
    					'value' => $this->language()->translate('core.search')
    			)
    	));
    	
    	$aFilter = $oFilter->getFilterValues();
    	
    	if($this->request()->isPost() && 
    			$this->request()->get('submit') == $this->language()->get('core.submit'))
    	{
    		$aParams = $this->request()->get('val');
    		if(count($aParams))
    		{
    			$oLanguagePatchModel = new LanguagePatchModel();
    			foreach($aParams as $iKey => $sValue)
    			{
    				$aParts = explode('|',$iKey); 
    				if(count($aParts) == 2)
    				{
    					try
    					{
    						$oExistPatch = $oLanguagePatchModel->getOne($aParts,array('language_code','var_name'));
    						if($oExistPatch && $oExistPatch->phrase_id)
    						{
    							$oExistPatch->value = $sValue;
    							$oExistPatch->update();
    						}
    						else
    						{
    							$aInsert = array(
    									'var_name' => $aParts[1],
    									'language_code' => $aParts[0],
    									'value' => $sValue
    							);
    							$oNewPatch = $oLanguagePatchModel->getTable()->createRow($aInsert);
    							$oNewPatch->save();
    						}	
    					}
    					catch(\Exception $ex)
    					{
    							
    					}
    					
    				}
    			}
    		}
    		$aCacheSearchParams = array(
    			'admincp' => true,
    		);
    		foreach($aFilter as $sKey => $sFilter)
    		{
    			$aCacheSearchParams['filter['.$sKey.']'] = $sFilter;
    		}
    		
    		$this->url()->redirect('core/language/customize',$aCacheSearchParams,$this->language()->translate('core.updated_language_phrases_successfully'));
    	}
    	
    	$aConds = array();
    	if (isset($aFilter) && count($aFilter))
    	{
    		$sPhraseVar = isset($aFilter['phrase_var']) ? $aFilter['phrase_var']: ""; 
    		$sPhraseVar = trim($sPhraseVar);
    		$sLanguageCode = isset($aFilter['language']) ? $aFilter['language']: ""; 
    		
    		$aPhrases = $oModelLanguage->getPhrases($sLanguageCode);
    		$iLimitDisplay = isset($aFilter['limit']) ? $aFilter['limit']: 10;
    		$aFoundItems = array();
    		if(count($aPhrases) && !empty($sPhraseVar))
    		{
    			foreach($aPhrases as $iKey => $aPhrase)
    			{
    				if(count($aFoundItems) > $iLimitDisplay)
    				{
    					break;
    				}
    				if(is_array($aPhrase))
    				{
    					foreach($aPhrase as $iKey2 => $sSubPhrase)
    					{
    						if(strpos($iKey2, $sPhraseVar) !== false || strpos($sSubPhrase, $sPhraseVar))
    						{
    							$aFoundItems[$iKey."|".$iKey2] = array(
    								'language' => $iKey,
    								'value' => $aPhrase[$iKey2],
    								'key' => $iKey2,
    							);
    						}
    					}
    				}
    				else
    				{
    					if(strpos($iKey, $sPhraseVar) !== false || strpos($aPhrase, $sPhraseVar))
    					{
    						$aFoundItems[$sLanguageCode."|".$iKey] = array(
    							'language' => $sLanguageCode,
    							 'value' => $aPhrases[$iKey],
    							 'key' => $iKey,
    						);
    					}
    				}
    			}
    		}
    		if(!count($aFoundItems))
    		{
    			$sPhraseVarKey =  $oModelLanguage->getPhraseVarName($sPhraseVar);
    			$aParts = explode('.',$sPhraseVarKey);
    			if(count($aParts) != 2)
    			{
    				$sPhraseVarKey = "language.".$sPhraseVarKey; 
    			}
    			if(empty($sLanguageCode))
    			{
    				foreach($aLanguages as $oLanguage)
    				{
    					$aFoundItems[$oLanguage->language_code."|new-one"] = array(
    							'language' => $oLanguage->language_code,
    							'value' => $sPhraseVar,
    							'key' => $sPhraseVarKey,
    					);
    				}
    			}
    			else
    			{
    				$aFoundItems[$sLanguageCode."|new-one"] = array(
    						'language' => $sLanguageCode,
    						'value' => $sPhraseVar,
    						'key' => $sPhraseVarKey,
    				);
    			}
    		}
    		$this->view->aFoundItems = $aFoundItems;
    		$this->view->bHasSearch = true;
    	}
    	$aBreadCrumb = array(
    			'title' => $this->language()->translate('core.customize_languages'),
    			'extra_title' => '',
    			'icon' => '',
    			'url' => 'javascript:void(0);',
    			'title_extra' => '',
    			'path' => array(
    					$this->url()->makeUrl('core/language', array('admincp' => true)) => $this->language()->translate('core.language'),
    			),
    	);
    	$this->template()->setBreadCrumb($aBreadCrumb);
    }
	public function AddAction()
	{
		$oModelLanguage = (new ModelLanguage());
		$this->view->aLanguages = $aLanguages = $oModelLanguage->getAll();
		$sPhraseVar = $this->request()->get('phrase_var');
		if($this->request()->isPost() && !empty($sPhraseVar))
		{
			
			$sPhraseVarKey =  $oModelLanguage->getPhraseVarName($sPhraseVar);
			$aParts = explode('.',$sPhraseVarKey);
			if(count($aParts) != 2)
			{
				$sPhraseVarKey = "language.".$sPhraseVarKey;
			}
			$aFilter = array(
					'admincp' => true,
					'filter[phrase_var]' => $sPhraseVarKey
			);
			$aPhrases = $oModelLanguage->getPhrases();
			foreach($aPhrases as $iKey => $aPhrase)
			{
				foreach($aPhrase as $iKey2 => $sPhraseTmp)
				{
					if($iKey2 === $sPhraseVarKey)
					{
						$this->url()->redirect('core/language/customize',$aFilter, $this->language()->translate('core.phrase_has_been_existed_please_update_only'));
					}
				}
			}	
			$aPhraseValues = $this->request()->get('phrase_value');
			
			$oLanguagePatchModel = new LanguagePatchModel();
			foreach($aPhraseValues as $iKey => $sValue)
			{
				try
				{
					$aInsert = array(
							'var_name' => $sPhraseVarKey,
							'language_code' => $iKey,
							'value' => $sValue
					);
					$oNewPatch = $oLanguagePatchModel->getTable()->createRow($aInsert);
					$oNewPatch->save();
				}
				catch(\Exception $ex)
				{
						
				}
			}
			$this->url()->redirect('core/language/customize',$aFilter, $this->language()->translate('core.added_phrase_successfully'));
		}
		$this->template()->setHeader(array(
			'admin_language.css' => 'module_core',
		));
		$aBreadCrumb = array(
				'title' => $this->language()->translate('core.add_phrase'),
				'extra_title' => '',
				'icon' => '',
				'url' => 'javascript:void(0);',
				'title_extra' => '',
				'path' => array(
						$this->url()->makeUrl('core/language', array('admincp' => true)) => $this->language()->translate('core.language'),
				),
		);
		$this->template()->setBreadCrumb($aBreadCrumb);
	}
}
