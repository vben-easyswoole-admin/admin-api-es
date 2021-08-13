<?php


namespace App\HttpController\Admin;


use App\HttpController\BaseController;
use EasySwoole\EasySwoole\Trigger;
use EasySwoole\HttpAnnotation\Exception\Annotation\ParamValidateError;


class AdminBase extends BaseController
{

    //超管id
    protected $superAdmin = 1;
    protected function onException(\Throwable $throwable): void
    {
        if($throwable instanceof ParamValidateError){
            $this->writeJson(400,null,"参数【{$throwable->getValidate()->getError()->getErrorRuleMsg()}】");
        }else{
            //拦截错误进日志,使控制器继续运行
            Trigger::getInstance()->throwable($throwable);
            $this->writeJson(\EasySwoole\Http\Message\Status::CODE_INTERNAL_SERVER_ERROR, null,$throwable->getMessage());
        }
    }


}
