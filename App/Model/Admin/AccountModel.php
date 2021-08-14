<?php


namespace App\Model\Admin;


use App\Model\BaseModel;
use App\Utility\Helper;
use EasySwoole\ORM\DbManager;
use EasySwoole\EasySwoole\Logger;

class AccountModel extends BaseModel
{
    protected $tableName = 'admin_account';
    protected $autoTimeStamp = true;

    /**
     * 修改器，将密码加密
     * @param $value
     * @return false|string|null
     */
    protected function setPwdAttr($value)
    {
        return password_hash($value,PASSWORD_DEFAULT);
    }

    public function getOne($id)
    {
        return $this->get($id)->hidden('pwd');
    }

    public function getAccountExist($account,$not_id = null)
    {
        $query = $this->where('account',$account);
        if($not_id){
            $query->where('id',$not_id,'<>');
        }
        return $query->get();
    }

    public function getPageData($params)
    {
        $query = $this->withTotalCount();

        if(Helper::array_get($params,'nickname')){
            $where['nickname'] = ['%' . $params['nickname'] . '%', 'like'];
            $query->where($where);
        }

        if(Helper::array_get($params,'account')){
            $query->where('account',$params['account']);
        }

        if(Helper::array_get($params,'status')){
            $query->where('status',$params['status']);
        }

        if(!Helper::array_get($params,'limit')){
            $params['limit'] = $this->limit;
        }

        $list = $query->order('id','ASC')
            ->limit($params['limit'] * ($params['page'] - 1), $params['limit'])
            ->all()
            ->hidden('pwd');

        return $this->pageFormat($list,$params['page'],$params['limit'],$this->lastQueryResult()->getTotalCount());
    }

    public function createAccount($data)
    {
        try {
            // 开启事务
            DbManager::getInstance()->startTransaction();

            $role_ids = $data['role_ids'];
            unset($data['role_ids']);

            $account_id = $this->data($data)->save();

            $accountRole = [];
            foreach ($role_ids as $k=>$role_id){
                $accountRole[$k]['account_id'] = $account_id;
                $accountRole[$k]['role_id'] = $role_id;
            }

            $re = AccountRoleModel::create()->saveAll($accountRole);

            if($account_id && $re){
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

    public function updateAccount($id,$data)
    {
        try {
            // 开启事务
            DbManager::getInstance()->startTransaction();

            $role_ids = $data['role_ids'];
            unset($data['role_ids']);

            $account = $this->where('id',$data)->update($data);
            $role_ids_data = AccountRoleModel::create()->where('account_id',$id)->column('role_id');

            if($role_ids != $role_ids_data){
                $de = AccountRoleModel::create()->where('acccount_id',$id)->destroy();
                $accountRole = [];
                foreach ($role_ids as $k=>$role_id){
                    $accountRole[$k]['account_id'] = $id;
                    $accountRole[$k]['role_id'] = $role_id;
                }
                $re = AccountRoleModel::create()->saveAll($accountRole);
            }else{
                $de = true;
                $re = true;
            }


            if($account && $re && $de){
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

    public function deleteAccount($id)
    {
        try {
            // 开启事务
            DbManager::getInstance()->startTransaction();

            $account = $this->where('id',$id)->destroy();
            $accountRole = AccountRoleModel::create()->where('acccount_id',$id)->destroy();

            if($account && $accountRole){
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
