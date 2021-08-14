<?php


namespace EasySwoole\EasySwoole;


use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\Redis\Config\RedisConfig;

class EasySwooleEvent implements Event
{
    public static function initialize()
    {
        //设置时区
        date_default_timezone_set('Asia/Shanghai');

        //注册Mysql连接池
        $config = new \EasySwoole\ORM\Db\Config(Config::getInstance()->getConf('MYSQL'));
        $config->setMaxObjectNum(20);
        $config->setReturnCollection(true);
        \EasySwoole\ORM\DbManager::getInstance()->addConnection(new Connection($config));

        //处理跨域
        \EasySwoole\Component\Di::getInstance()->set(\EasySwoole\EasySwoole\SysConst::HTTP_GLOBAL_ON_REQUEST, function (\EasySwoole\Http\Request $request, \EasySwoole\Http\Response $response) {
            $origin = $request->getHeader('origin')[0] ?? '*';
            $response->withHeader('Access-Control-Allow-Origin', $origin);
            $response->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->withHeader('Access-Control-Allow-Credentials', 'true');
            $response->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, token');
            if ($request->getMethod() === 'OPTIONS') {
                $response->withStatus(\EasySwoole\Http\Message\Status::CODE_OK);
                return false;
            }
            return true;
        });
    }

    public static function mainServerCreate(EventRegister $register)
    {
        //注册Redis连接池
        \EasySwoole\RedisPool\RedisPool::getInstance()->register(new RedisConfig(Config::getInstance()->getConf('REDIS')));

    }
}
