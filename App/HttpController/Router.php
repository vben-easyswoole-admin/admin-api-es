<?php


namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\AbstractRouter;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use FastRoute\RouteCollector;

class Router extends AbstractRouter
{
    function initialize(RouteCollector $routeCollector)
    {
        //后台
        $routeCollector->addGroup('/admin',function (RouteCollector $collector){

            //登陆
            $collector->addRoute('POST','/login','/Admin/Auth/login');

            //首页
            $collector->addRoute('GET','','/Admin/Index/index');

            //账号管理
            $collector->addRoute('GET','/account','/Admin/Account/index');
            $collector->addRoute('GET','/account/create','/Admin/Account/create');
            $collector->addRoute('POST','/account','/Admin/Account/save');
            $collector->addRoute('GET','/account/{id:\d+}/edit','/Admin/Account/edit');
            $collector->addRoute('PUT','/account/{id:\d+}','/Admin/Account/update');
            $collector->addRoute('DELETE','/account/{id:\d+}','/Admin/Account/delete');
            $collector->addRoute('GET','/account/{id:\d+}/status','/Admin/Account/status');

            //角色管理
            $collector->addRoute('GET','/role','/Admin/Role/index');
            $collector->addRoute('GET','/role/create','/Admin/Role/create');
            $collector->addRoute('POST','/role','/Admin/Role/save');
            $collector->addRoute('GET','/role/{id:\d+}/edit','/Admin/Role/edit');
            $collector->addRoute('PUT','/role/{id:\d+}','/Admin/Role/update');
            $collector->addRoute('DELETE','/role/{id:\d+}','/Admin/Role/delete');
            $collector->addRoute('GET','/role/{id:\d+}/status','/Admin/Role/status');

            //菜单管理
            $collector->addRoute('GET','/menu','/Admin/Menu/index');
            $collector->addRoute('GET','/menu/create','/Admin/Menu/create');
            $collector->addRoute('POST','/menu','/Admin/Menu/save');
            $collector->addRoute('GET','/menu/{id:\d+}/edit','/Admin/Menu/edit');
            $collector->addRoute('PUT','/menu/{id:\d+}','/Admin/Menu/update');
            $collector->addRoute('DELETE','/menu/{id:\d+}','/Admin/Menu/delete');
            $collector->addRoute('GET','/menu/{id:\d+}/status','/Admin/Menu/status');

        });


        //设置路由参数接收类型
        $this->parseParams(\EasySwoole\Http\AbstractInterface\AbstractRouter::PARSE_PARAMS_IN_GET);
        $this->setGlobalMode(true);
        $this->setMethodNotAllowCallBack(function (Request $request,Response $response){
            $response->write('error');
            return false;//结束此次响应
        });
        $this->setRouterNotFoundCallBack(function (Request $request,Response $response){
            $response->write('error');
            return false;//结束此次响应
        });
    }
}
