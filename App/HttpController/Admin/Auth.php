<?php


namespace App\HttpController\Admin;

use App\Model\Admin\AccountModel;
use App\Model\Admin\AccountRoleModel;
use App\Model\Admin\MenuModel;
use App\Model\Admin\RoleMenuModel;
use App\Utility\Jwt;
use EasySwoole\HttpAnnotation\AnnotationTag\Param;


class Auth extends AdminBase
{
    private $timeout = 3600*12;
    private $cache_permission_key = 'account-permission-';

    /**
     * @Param(name="account",required="")
     * @Param(name="pwd",required="")
     */
    public function login()
    {

        $params = $this->request()->getRequestParam('account','pwd');
        $account = AccountModel::create()->where('account',$params['account'])->get();

        if(!$account){
            return $this->response_error('账号不存在');
        }

        if(!password_verify($params['pwd'],$account->pwd)){
            return $this->response_error('密码错误');
        }

        if($account->status != 1){
            return $this->response_error('账号被封禁');
        }

        //判断是否超管
        if($account->id != $this->superAdmin){
            $accountRoleModel = new AccountRoleModel();
            $role_ids = $accountRoleModel->getRoleStatus($account->id);
            if(!$role_ids){
                return $this->response_error('账号被封禁');
            }

            //获取菜单和权限
            $menuPermission = $this->getMenuPermission($role_ids);

            //将权限写入redis
            $this->redisInvokeSet($this->cache_permission_key.$account->id,serialize($menuPermission['permission']),$this->timeout - 2);
        }else{
            $menuPermission = $this->getMenuAdmin();
        }

        $tokenData = ['account_id' => $account->id];
        $jwt = new Jwt();
        $token = $jwt->createToken($tokenData,$this->timeout);

        if(!$token){
            return $this->response_error('登录失败',500);
        }

        $result = [
            'account_id' => $account->id,
            'token' => $token,
            'expiration_time' => time() + $this->timeout,
            'menu' => $menuPermission['menu'],
            'permission' => $menuPermission['permission']
        ];

        return $this->response_success($result);
    }

    protected function getMenuPermission($role_ids)
    {
        $roleMenuModel = new RoleMenuModel();
        $menu_ids = $roleMenuModel->getMenuId($role_ids);

        var_dump($menu_ids);

        $menuModel = new MenuModel();
        $menu = $menuModel->getTreeList($menu_ids);

        $permission = $menuModel->getPermission($menu_ids);

        return [
            'menu' => $menu,
            'permission' => $permission
        ];
    }

    protected function getMenuAdmin()
    {
        $menuModel = new MenuModel();
        $menu = $menuModel->getTreeList();

        $permission = $menuModel->getPermission();

        return [
            'menu' => $menu,
            'permission' => $permission
        ];
    }


}
