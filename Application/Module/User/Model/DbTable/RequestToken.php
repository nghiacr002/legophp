<?php 
namespace APP\Application\Module\User\Model\DbTable;
class RequestToken extends \APP\Engine\Database\DbTable
{
	protected $_sTableName = "request_token";
	protected $_mPrimaryKey = "token_id";
	protected $_aValidateRules = array(
		'required' => array(
			array('user_id'),array('code'),array('status'),array('request_type')
		)
	);
	protected $_sRowClass = "\APP\Application\Module\User\Model\DbTable\DbRow\RequestToken";
}
