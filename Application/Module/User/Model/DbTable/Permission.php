<?php

namespace APP\Application\Module\User\Model\DbTable;

use APP\Application\Module\User\Model\Permission as PermModel;

class Permission extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "permission";
    protected $_mPrimaryKey = "permission_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('module'), array('var_name')
        ),
    );
    protected $_sRowClass = "\\APP\\Application\\Module\User\Model\DbTable\\DbRow\\Permission";

    public function businessValidate(\APP\Engine\Database\DbRow $oPerm)
    {
        $oPermModel = new PermModel();
        $oExistedPerm = $oPermModel->getOne(array(
            $oPerm->module,
            $oPerm->var_name,
                ), array(
            'module',
            'var_name',
        ));
        if ($oExistedPerm && $oExistedPerm->permission_id)
        {
            return false;
        }
        return true;
    }

}
