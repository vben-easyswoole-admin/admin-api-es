<?php


namespace App\HttpController\Admin;


use App\Model\Admin\AccountModel;
use App\Model\Admin\AccountRoleModel;
use App\Model\Admin\RoleModel;
use EasySwoole\HttpAnnotation\AnnotationTag\Param;

class Account extends AuthBase
{
    /**
     * @Param(name="page",required="",integer="",min="1",max="100")
     * @Param(name="limit",optional="",integer="",min="10",max="100")
     */
    public function index()
    {
        $params = $this->request()->getRequestParam('page','limit','account','nickname','status');
        $model = new AccountModel();
        return $this->response_success($model->getPageData($params));
    }

    public function create()
    {
        $model = new RoleModel();
        return $this->response_success(['roles'=>$model->getAll()]);
    }

    /**
     * @Param(name="account",required="",lengthMin="4",lengthMax="16")
     * @Param(name="pwd",required="",lengthMin="6",lengthMax="16")
     * @Param(name="nickname",required="",mbLengthMin="2",mbLengthMax="16")
     * @Param(name="email",required="")
     * @Param(name="remark",required="",mbLengthMax="32")
     * @Param(name="status",optional="",inArray=[0,1])
     * @Param(name="role_ids",required="")
     */
    public function save()
    {
        $params = $this->request()->getRequestParam('account','pwd','nickname','email','remark','status','role_ids');
        $model = new AccountModel();

        if($model->getAccountExist($params['account'])){
            return $this->response_error('该账号已存在');
        }

        $re = $model->createAccount($params);

        if(!$re){
            return $this->response_error('新增账号失败');
        }

        return $this->response_info('新增账号成功');
    }

    public function edit()
    {
        $id = $this->request()->getQueryParam('id');
        $model = new AccountModel();
        $accountRoleModel = new AccountRoleModel();
        $roleModel = new RoleModel();

        $result = [
            'account' => $model->getOne($id),
            'account_role' => $accountRoleModel->getAccountRole($id),
            'roles' => $roleModel->getAll()
        ];

        return $this->response_success($result);
    }

    /**
     * @Param(name="account",required="",lengthMin="4",lengthMax="16")
     * @Param(name="pwd",required="",lengthMin="6",lengthMax="16")
     * @Param(name="nickname",required="",mbLengthMin="2",mbLengthMax="16")
     * @Param(name="email",required="")
     * @Param(name="remark",required="",mbLengthMax="32")
     * @Param(name="status",optional="",inArray=[0,1])
     * @Param(name="role_ids",required="")
     */
    public function update()
    {
        $id = $this->request()->getQueryParam('id');
        $params = $this->request()->getRequestParam('account','pwd','nickname','email','remark','status','role_ids');

        $model = new AccountModel();

        if($model->getAccountExist($params['account'],$id)){
            return $this->response_error('该账号已存在');
        }

        $re = $model->updateAccount($id,$params);

        if(!$re){
            return $this->response_error('编辑账号失败');
        }

        return $this->response_info('编辑账号成功');
    }

    public function delete()
    {
        $id = $this->request()->getQueryParam('id');

        //判断是否超管
        if($id == $this->superAdmin){
            return $this->response_error('超级管理员不允许删除');
        }

        $model = new AccountModel();
        $re = $model->deleteAccount($id);

        if(!$re){
            return $this->response_error('删除账号失败');
        }

        return $this->response_info('删除账号成功');
    }

    public function status()
    {
        $id = $this->request()->getQueryParam('id');

        //判断是否超管
        if($id == $this->superAdmin){
            return $this->response_error('超级管理员不允许关闭');
        }

        $model = new AccountModel();
        $re = $model->setStatus($id);

        if(!$re){
            return $this->response_error('设置状态失败');
        }

        return $this->response_info('设置状态成功');
    }


}
