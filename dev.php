<?php

use EasySwoole\Log\LoggerInterface;

return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9502,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SERVER,
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'reload_async' => true,
            'max_wait_time' => 3
        ],
        'TASK' => [
            'workerNum' => 4,
            'maxRunningNum' => 128,
            'timeout' => 15
        ]
    ],
    "LOG" => [
        'dir' => null,
        'level' => LoggerInterface::LOG_LEVEL_DEBUG,
        'handler' => null,
        'logConsole' => true,
        'displayConsole' => true,
        'ignoreCategory' => []
    ],
    'TEMP_DIR' => null,

    'JWTSECRETKEY' => 'K1h9ecDHSAt3EFNkl0IOzk54IkC7mVUXkof68GjZiGbQTBEcuarm9KFwiNprq2Rc',

    'MYSQL' => [
        //数据库配置
        'host'                 => '127.0.0.1',
        'user'                 => 'es_admin',
        'password'             => 'CcXpnTPCWpjJpz4S',
        'database'             => 'es_admin',
        'port'                 => '3306',
        'timeout'              => '30',
        'connect_timeout'      => '5',
        'charset'              => 'utf8mb4',
        'max_reconnect_times ' => '3',
    ],

    'REDIS' => [
        //数据库配置
        'host'                 => '127.0.0.1',
        'port'                 => '6379',
        'timeout'              => '3.0',
        'reconnectTimes'       => '3',
        'auth'                 => '',
        'serialize'            => \EasySwoole\Redis\Config\RedisConfig::SERIALIZE_NONE
    ],
];
