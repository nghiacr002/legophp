<?php 
namespace APP\Application\Module\Core\Model\DbTable;
class LanguagePatch extends \APP\Engine\Database\DbTable
{
	protected $_sTableName = "language_patch";
	protected $_mPrimaryKey = "phrase_id";
	protected $_aValidateRules = array(
		'required' => array(
			array('var_name'),array('language_code')
		)
	);
	protected $_sRowClass = "\APP\Application\Module\Core\Model\DbTable\DbRow\LanguagePatch";
}
