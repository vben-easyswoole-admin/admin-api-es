<?php


namespace App\Utility;


use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\Logger;

class Jwt
{
    /**
     * 发布密钥
     * @param array $data
     * @param int $timeout
     * @return false|string
     */
    public function createToken(array $data,int $timeout)
    {
        try {
            $secretKey = Config::getInstance()->getConf('JWTSECRETKEY');
            $jwtObject = \EasySwoole\Jwt\Jwt::getInstance()
                ->setSecretKey($secretKey)
                ->publish();

            $jwtObject->setAlg('HMACSHA256');
            $jwtObject->setAud('user');
            $jwtObject->setExp(time()+$timeout);
            $jwtObject->setIat(time());
            $jwtObject->setIss('dalang');
            $jwtObject->setJti(md5(time()));
            $jwtObject->setNbf(time());
            $jwtObject->setSub('JWT');

            $jwtObject->setData($data);

            $token = $jwtObject->__toString();

            return $token;

        }catch (\EasySwoole\Jwt\Exception $exception){

            Logger::getInstance()->error($exception->getMessage());
            return false;
        }

    }

    /**
     * 解密密钥
     * @param string $token
     * @return false
     */
    public function decodeToken(string $token)
    {
        try {
            $secretKey = Config::getInstance()->getConf('JWTSECRETKEY');
            $jwtObject = \EasySwoole\Jwt\Jwt::getInstance()->setSecretKey($secretKey)->decode($token);

            $status = $jwtObject->getStatus();

            if($status != 1){
                return false;
            }

            $jwtData = $jwtObject->getData();

            return $jwtData;

        } catch (\EasySwoole\Jwt\Exception $exception) {
            Logger::getInstance()->error($exception->getMessage());
            return false;
        }

    }




}
