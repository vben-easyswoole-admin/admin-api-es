<?php


namespace App\HttpController;

use EasySwoole\HttpAnnotation\AnnotationController;
use EasySwoole\RedisPool\RedisPool;

class BaseController extends AnnotationController
{
    public function response_success($data = null,string $msg = 'success')
    {
        $this->writeJson(200,$data,$msg);
    }

    public function response_info(string $msg = 'success')
    {
        $this->writeJson(200,null,$msg);
    }

    public function response_error(string $msg = 'Request error',int $status = 400)
    {
        $this->writeJson($status,null,$msg);
    }


    protected function redisInvokeGet(string $key)
    {
        $data = RedisPool::invoke(function (\EasySwoole\Redis\Redis $redis) use($key){
            return $redis->get($key);
        });
        return $data;
    }

    protected function redisInvokeSet(string $key,string $value,int $timeout = 0)
    {
        $data = RedisPool::invoke(function (\EasySwoole\Redis\Redis $redis) use($key,$value,$timeout){
            return $redis->set($key,$value,$timeout);
        });
        return $data;
    }

    protected function redisInvokeDel(string $key)
    {
        $data = RedisPool::invoke(function (\EasySwoole\Redis\Redis $redis) use($key){
            return $redis->del($key);
        });
        return $data;
    }

}
