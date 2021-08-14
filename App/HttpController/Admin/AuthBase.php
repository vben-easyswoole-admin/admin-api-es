<?php


namespace App\HttpController\Admin;


use App\Model\Admin\AccountModel;
use App\Model\Admin\AccountRoleModel;
use App\Utility\Jwt;

class AuthBase extends AdminBase
{
    public $accountID;
    private $cache_permission_key = 'account-permission-';

    public function onRequest(?string $action): ?bool
    {
        if (!parent::onRequest($action)) {
            return false;
        }

        $headerToken = $this->request()->getHeader('token');

        if(!$headerToken || !isset($headerToken[0]) || !$headerToken[0]){
            $this->response_error('请登录后再操作',401);
            return false;
        }

        $token = $headerToken[0];

        $jwt = new Jwt();
        $tokenData = $jwt->decodeToken($token);

        if(!$tokenData){
            $this->response_error('请重新登录',401);
            return false;
        }

        if(!isset($tokenData['account_id']) || !$tokenData['account_id']){
            $this->response_error('请登录后再操作',401);
            return false;
        }


        $accountModel = new AccountModel();
        $account = $accountModel->getOne($tokenData['account_id']);

        if(!$account || $account->status != 1){
            $this->response_error('账户被封禁',401);
            return false;
        }

        //非超管效验权限
        if($tokenData['account_id'] != $this->superAdmin){

            $accountRoleModel = new AccountRoleModel();
            $role_ids = $accountRoleModel->getRoleStatus($account->id);
            if(!$role_ids){
                return $this->response_error('账号被封禁');
            }

            $cache_key = $this->cache_permission_key.$tokenData['account_id'];
            $permission = unserialize($this->redisInvokeGet($cache_key));

            $urlstr = $this->request()->getUri()->getPath();

            var_dump($permission,$urlstr);

            if(!in_array($urlstr,$permission)){
                $this->response_error('权限不足',401);
                return false;
            }

        }


        $this->accountID = $tokenData['account_id'];

        return true;
    }

}
