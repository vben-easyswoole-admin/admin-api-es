<?php


namespace App\Model\Admin;


use App\Model\BaseModel;

class RoleMenuModel extends BaseModel
{
    protected $tableName = 'admin_role_menu';
    protected $autoTimeStamp = false;

    public function getMenuId($role_ids)
    {
        return $this->where('role_id',$role_ids,'IN')->column('menu_id');
    }
}
