<?php


namespace App\Model\Admin;


use App\Model\BaseModel;

class AccountRoleModel extends BaseModel
{
    protected $tableName = 'admin_account_role';
    protected $autoTimeStamp = false;

    public function getAccountRole($account_id)
    {
        return $this->where('status',1)->where('account_id',$account_id)->column('role_id');
    }


}
