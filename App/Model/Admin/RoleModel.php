<?php


namespace App\Model\Admin;


use App\Model\BaseModel;
use App\Utility\Helper;
use EasySwoole\ORM\DbManager;
use EasySwoole\EasySwoole\Logger;

class RoleModel extends BaseModel
{
    protected $tableName = 'admin_role';
    protected $autoTimeStamp = true;

    public function getOne($id)
    {
        return $this->get($id);
    }

    public function getNameExist($name,$not_id = null)
    {
        $query = $this->where('role_name',$name);
        if($not_id){
            $query->where('id',$not_id,'<>');
        }
        return $query->get();
    }

    public function getAll()
    {
        return $this->field('id,role_name')->where('status',1)->all();
    }

    public function getPageData($params)
    {
        $query = $this->withTotalCount();

        if(Helper::array_get($params,'role_name')){
            $where['nickname'] = ['%' . $params['role_name'] . '%', 'like'];
            $query->where($where);
        }

        if(Helper::array_get($params,'status')){
            $query->where('status',$params['status']);
        }

        if(!Helper::array_get($params,'limit')){
            $params['limit'] = $this->limit;
        }

        $list = $query->order('id','DESC')
            ->limit($params['limit'] * ($params['page'] - 1), $params['limit'])
            ->all();

        return $this->pageFormat($list,$params['page'],$params['limit'],$this->lastQueryResult()->getTotalCount());
    }

    public function createRole($data)
    {
        try {
            // 开启事务
            DbManager::getInstance()->startTransaction();

            $menu_ids = $data['menu_ids'];
            unset($data['menu_ids']);

            $role_id = $this->data($data)->save();

            $roleMenu = [];
            foreach ($menu_ids as $k=>$menu_id){
                $roleMenu[$k]['role_id'] = $role_id;
                $roleMenu[$k]['menu_id'] = $menu_id;
            }
            $re = RoleMenuModel::create()->saveAll($roleMenu);

            if($role_id && $re){
                // 提交事务
                DbManager::getInstance()->commit();
                return true;
            }else{
                // 回滚事务
                DbManager::getInstance()->rollback();
                return false;
            }

        } catch(\Throwable  $e){
            // 回滚事务
            DbManager::getInstance()->rollback();
            return false;
        }

    }

    public function updateRole($id,$data)
    {
        try {
            // 开启事务
            DbManager::getInstance()->startTransaction();

            $menu_ids = $data['menu_ids'];
            unset($data['menu_ids']);

            $role = $this->where('id',$data)->update($data);
            $menu_ids_data = RoleMenuModel::create()->where('role_id',$id)->column('menu_id');

            $diff = array_diff($menu_ids,$menu_ids_data);

            if($diff){
                $de = RoleMenuModel::create()->where('role_id',$id)->destroy();
                $roleMenu = [];
                foreach ($menu_ids as $k=>$menu_id){
                    $roleMenu[$k]['role_id'] = $id;
                    $roleMenu[$k]['menu_id'] = $menu_id;
                }
                $re = RoleMenuModel::create()->saveAll($roleMenu);
            }else{
                $de = true;
                $re = true;
            }


            if($role && $re && $de){
                // 提交事务
                DbManager::getInstance()->commit();
                return true;
            }else{
                // 回滚事务
                DbManager::getInstance()->rollback();
                return false;
            }

        } catch(\Throwable  $e){
            // 回滚事务
            DbManager::getInstance()->rollback();
            Logger::getInstance()->info(json_encode($e->getMessage()));
            return false;
        }
    }

    public function deleteRole($id)
    {
        try {
            // 开启事务
            DbManager::getInstance()->startTransaction();

            $role = $this->where('id',$id)->destroy();
            $role_menu = RoleMenuModel::create()->where('role_id',$id)->destroy();

            if($role && $role_menu){
                // 提交事务
                DbManager::getInstance()->commit();
                return true;
            }else{
                // 回滚事务
                DbManager::getInstance()->rollback();
                return false;
            }

        } catch(\Throwable  $e){
            // 回滚事务
            DbManager::getInstance()->rollback();
            return false;
        }
    }

    public function setStatus($id)
    {
        $data = $this->get($id);
        $data->status = $data->status == 1 ? 0 : 1;
        $re = $data->update();
        return $re;
    }



}
