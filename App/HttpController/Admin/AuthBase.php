<?php


namespace App\HttpController\Admin;


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
            $this->response_error('请登录后再操作1',401);
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

        //效验权限
        if($tokenData['account_id'] != $this->superAdmin){
            $cache_key = $this->cache_permission_key.$tokenData['account_id'];
            $permission = unserialize($this->redisInvokeGet($cache_key));

            $urlstr = $this->request()->getUri()->getPath();

            if(!in_array($urlstr,$permission)){
                $this->response_error('权限不足',401);
                return false;
            }

        }


        $this->accountID = $tokenData['account_id'];

        return true;
    }

}
