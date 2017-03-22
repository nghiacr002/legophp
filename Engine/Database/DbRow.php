<?php

namespace APP\Engine\Database;

use APP\Engine\Object;

class DbRow extends \APP\Engine\Object
{

    protected $_oTable;
    protected $_oValidator;
    protected $_aErrors;

    public function __construct($mTableName = "")
    {
        if (is_object($mTableName))
        {
            $this->_oTable = $mTableName;
        } else
        {
            $this->_oTable = new \APP\Engine\Database\DbTable($mTableName);
        }
    }

    public function getTable()
    {
        return $this->_oTable;
    }

    public function mapData($aData)
    {
        foreach ($aData as $iKey => $mData)
        {
            if (isset($this->_aData[$iKey]))
            {
                $this->_aData[$iKey] = $mData;
            }
        }
        return $this;
    }

    public function mergeData($data)
    {
        if (is_array($data))
        {
            foreach ($data as $key => $value)
            {
                $this->_aData[$key] = $value;
            }
        }
        return $this;
    }

    public function setData($data)
    {
        $this->_aData = $data;
        return $this;
    }

    public function unsetData($sKey)
    {
        if (isset($this->_aData[$sKey]))
        {
            unset($this->_aData[$sKey]);
        }
        return $this;
    }

    public function getErrors()
    {
        return $this->_aErrors;
    }

    public function setError($sError)
    {
        $this->_aErrors[] = $sError;
        return $this;
    }

    public function validator()
    {
        if (!$this->_oValidator)
        {
            $this->_oValidator = new \APP\Engine\Validator($this->_aData);
        }
        return $this->_oValidator;
    }

    public function isValid()
    {
        $this->_aErrors = array();
        $aValidRules = $this->_oTable->getValidateRules();
        $this->validator()->rules($aValidRules);
        $bValidate = $this->validator()->validate();
        if ($bValidate)
        {
            $bValidate = $this->_oTable->businessValidate($this);
        }
        if ($bValidate)
        {
            return true;
        }
        foreach ($this->_oValidator->errors() as $key => $error)
        {
            $this->_aErrors[$key] = implode(',', $error);
        }
        return false;
    }

    public function save()
    {
    	$this->beforeSave();
        $query = new Query();
        $query->setCommand("insert");
        $query->setTableData($this->_oTable->getTableName(), $this->_aData);
        $mResult = $this->_oTable->executeQuery($query);
        $this->_oTable->cleanCache();
        return $mResult;
    }
    public function update()
    {
    	$this->beforeUpdate();
        $query = new Query();
        $query->setCommand("update");
        $query->setTableData($this->_oTable->getTableName(), $this->_aData);
        $this->_buildWherePrimary($query);
        $bResult = $this->_oTable->executeQuery($query);
        $this->_oTable->cleanCache();
        return $bResult;
    }
    public function delete()
    {
    	$this->beforeDelete();
        $query = new Query();
        $query->setCommand("delete");
        $this->_buildWherePrimary($query);
        $query->from($this->_oTable->getTableName());
        $bResult = $this->_oTable->executeQuery($query);
        //$this->catchError();
        $this->_oTable->cleanCache();
        return $bResult;
    }
    public function beforeSave()
    {

    }
    public function beforeUpdate()
    {

    }
    public function beforeDelete()
    {

    }
    protected function catchError()
    {
        if ($this->_oTable->getAdapter()->hasError())
        {
            foreach ($this->_oTable->getAdapter()->getErrors() as $iKey => $aError)
            {
                $this->setError($aError);
            }
        }
        return $this;
    }

    protected function _buildWherePrimary($query)
    {
        $mPrimaryKey = $this->_oTable->getPrimaryKey();
        if (!is_array($mPrimaryKey))
        {
            $mPrimaryKey = array($mPrimaryKey);
        }
        foreach ($mPrimaryKey as $sPrimaryKey)
        {
            $query->where($sPrimaryKey, $this->{$sPrimaryKey});
        }
    }

    public function toArray()
    {
        return $this->_aData;
    }

    public function href()
    {
        return "#";
    }

}
