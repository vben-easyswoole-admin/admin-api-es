<?php


namespace App\HttpController\Admin;


use App\Model\Admin\MenuModel;
use App\Model\Admin\RoleMenuModel;
use App\Model\Admin\RoleModel;
use EasySwoole\HttpAnnotation\AnnotationTag\Param;

class Role extends AuthBase
{
    /**
     * @Param(name="page",required="",integer="",min="1",max="100")
     * @Param(name="limit",optional="",integer="",min="10",max="100")
     */
    public function index()
    {
        $params = $this->request()->getRequestParam('page','limit','role_name','status');
        $model = new RoleModel();
        return $this->response_success($model->getPageData($params));
    }

    public function create()
    {
        $model = new MenuModel();
        return $this->response_success(['menus'=>$model->getTreeList()]);
    }

    /**
     * @Param(name="role_name",required="",lengthMin="4",lengthMax="16")
     * @Param(name="remark",optional="",mbLengthMax="32")
     * @Param(name="status",required="",inArray=[0,1])
     * @Param(name="menu_ids",required="")
     */
    public function save()
    {
        $params = $this->request()->getRequestParam('role_name','remark','status','menu_ids');
        $model = new RoleModel();

        if($model->getNameExist($params['role_name'])){
            return $this->response_error('该角色名称已存在');
        }

        $re = $model->createRole($params);

        if(!$re){
            return $this->response_error('新增角色失败');
        }

        return $this->response_info('新增角色成功');
    }

    public function edit()
    {
        $id = $this->request()->getQueryParam('id');
        $model = new RoleModel();
        $roleMenuModel = new RoleMenuModel();
        $menuModel = new MenuModel();

        $result = [
            'role' => $model->getOne($id),
            'role_menu' => $roleMenuModel->getRoleMenu($id),
            'menus' => $menuModel->getTreeList()
        ];

        return $this->response_success($result);
    }

    /**
     * @Param(name="role_name",required="",lengthMin="4",lengthMax="16")
     * @Param(name="remark",optional="",mbLengthMax="32")
     * @Param(name="status",required="",inArray=[0,1])
     * @Param(name="menu_ids",required="")
     */
    public function update()
    {
        $id = $this->request()->getQueryParam('id');
        Logger::getInstance()->info(json_encode($id));
        $params = $this->request()->getRequestParam('role_name','remark','status','menu_ids');

        $model = new RoleModel();

        if($model->getNameExist($params['role_name'],$id)){
            return $this->response_error('该角色名称已存在');
        }

        $re = $model->updateRole($id,$params);

        if(!$re){
            return $this->response_error('编辑角色失败');
        }

        return $this->response_info('编辑角色成功');
    }

    public function delete()
    {
        $id = $this->request()->getQueryParam('id');
        $model = new RoleModel();
        $re = $model->deleteRole($id);

        if(!$re){
            return $this->response_error('删除角色失败');
        }

        return $this->response_info('删除角色成功');
    }

    public function status()
    {
        $id = $this->request()->getQueryParam('id');
        $model = new RoleModel();
        $re = $model->setStatus($id);

        if(!$re){
            return $this->response_error('设置状态失败');
        }

        return $this->response_info('设置状态成功');
    }

}
