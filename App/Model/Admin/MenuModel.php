<?php


namespace App\Model\Admin;


use App\Model\BaseModel;
use App\Utility\Helper;
use EasySwoole\ORM\DbManager;

class MenuModel extends BaseModel
{
    protected $tableName = 'admin_menu';
    protected $autoTimeStamp = true;

    public function getOne($id)
    {
        return $this->get($id);
    }

    public function getPageData($params)
    {
        $query = $this->withTotalCount();

        if(Helper::array_get($params,'menu_name')){
            $where['menu_name'] = ['%' . $params['menu_name'] . '%', 'like'];
            $query->where($where);
        }

        if(Helper::array_get($params,'status')){
            $query->where('status',$params['status']);
        }

        if(!Helper::array_get($params,'limit')){
            $params['limit'] = $this->limit;
        }

        $list = $query->order('sort','DESC')
            ->limit($params['limit'] * ($params['page'] - 1), $params['limit'])
            ->all();

        return $this->pageFormat($list,$params['page'],$params['limit'],$this->lastQueryResult()->getTotalCount());
    }

    public function createMenu($data)
    {
        return $this->data($data)->save();
    }

    public function updateMenu($id,$data)
    {
        return $this->where('id',$id)->update($data);
    }

    public function deleteMenu($id)
    {
        try {
            // 开启事务
            DbManager::getInstance()->startTransaction();

            $data = $this->where('id',$id)->destroy();
            $child = $this->where('menu_pid',$id)->get();

            if($child){
                $re = $child->destroy();
            }else{
                $re = true;
            }

            if($data && $re){
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

    public function getTreeList($ids = [])
    {

        $parent_one = [];
        $parent_two = [];
        $query = $this->where('status',1);
        if($ids){
            $query->where('id',$ids,'IN');
        }
        $menus = $query->order('sort','DESC')
            ->all()
            ->toArray();

        //查找上级
        $pids = array_union(array_column($menus, 'menu_pid'));

        $parent_one = $this->where('id',$pids,'IN')->order('sort','DESC')
            ->all()
            ->toArray();
        if($parent_one){

            $pid_one = array_union(array_column($menus, 'menu_pid'));
            $parent_two = $this->where('id',$pid_one,'IN')->order('sort','DESC')
                ->all()
                ->toArray();
        }    
            
        $data = array_merge($menus,$parent_one,$parent_two);
        
        return Helper::createTree($data);
    }

    public function getPermission($menu_ids = [])
    {
        $query = $this->where('status',1)
                 ->where('type',1,'<>');
        if($menu_ids){
            $query->where('id',$menu_ids,'IN');
        }
        return $query->column('permission');
    }


}
