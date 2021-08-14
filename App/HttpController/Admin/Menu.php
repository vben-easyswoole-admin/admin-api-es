<?php


namespace App\HttpController\Admin;


use App\Model\Admin\MenuModel;
use App\Utility\Helper;
use EasySwoole\HttpAnnotation\AnnotationTag\Param;

class Menu extends AuthBase
{
    /**
     * @Param(name="page",required="",integer="",min="1",max="100")
     * @Param(name="limit",optional="",integer="",min="10",max="100")
     */
    public function index()
    {
        $params = $this->request()->getRequestParam('page','limit','menu_name','status');
        $model = new MenuModel();
        return $this->response_success($model->getPageData($params));
    }

    public function create()
    {
        $model = new MenuModel();
        return $this->response_success(['menus'=>$model->getTreeList()]);
    }

    /**
     * @Param(name="type",required="",inArray=[1,2,3])
     * @Param(name="menu_name",required="",mbLengthMax="2",mbLengthMax="10")
     * @Param(name="menu_pid",optional="",integer="")
     * @Param(name="icon",optional="",lengthMax="32")
     * @Param(name="route_path",optional="",lengthMax="32")
     * @Param(name="component",optional="",lengthMax="32")
     * @Param(name="permission",optional="",lengthMax="32")
     * @Param(name="status",optional="",inArray=[0,1])
     * @Param(name="is_ext",optional="",inArray=[0,1])
     * @Param(name="show",optional="",inArray=[0,1])
     */
    public function save()
    {
        $params = $this->request()->getRequestParam('type','menu_name','menu_pid','icon','route_path',
            'component','permission','status','sort','is_ext','show');

        //不同类型菜单参数验证
        $validata = $this->menuValidate($params);
        if($validata != true){
            return $this->response_error($validata);
        }

        //不同菜单参数过滤
        $params = $this->paramsFilter($params);

        $model = new MenuModel();
        $re = $model->createMenu($params);

        if(!$re){
            return $this->response_error('新增菜单失败');
        }

        return $this->response_info('新增菜单成功');
    }

    public function edit()
    {
        $id = $this->request()->getQueryParam('id');
        $model = new MenuModel();

        $result = [
            'menu' => $model->getOne($id),
            'menus' => $model->getTreeList()
        ];

        return $this->response_success($result);
    }


    /**
     * @Param(name="type",required="",inArray=[1,2,3])
     * @Param(name="menu_name",required="",mbLengthMax="2",mbLengthMax="10")
     * @Param(name="menu_pid",optional="",integer="")
     * @Param(name="icon",optional="",lengthMax="32")
     * @Param(name="route_path",optional="",lengthMax="32")
     * @Param(name="component",optional="",lengthMax="32")
     * @Param(name="permission",optional="",lengthMax="32")
     * @Param(name="status",optional="",inArray=[0,1])
     * @Param(name="is_ext",optional="",inArray=[0,1])
     * @Param(name="show",optional="",inArray=[0,1])
     */
    public function update()
    {
        $id = $this->request()->getQueryParam('id');
        $params = $this->request()->getRequestParam('type','menu_name','menu_pid','icon','route_path',
            'component','permission','status','sort','is_ext','show');

        //不同类型菜单参数验证
        $validata = $this->menuValidate($params);
        if($validata != true){
            return $this->response_error($validata);
        }

        //不同菜单参数过滤
        $params = $this->paramsFilter($params);

        $model = new MenuModel();
        $re = $model->updateMenu($id,$params);

        if(!$re){
            return $this->response_error('编辑菜单失败');
        }

        return $this->response_info('编辑菜单成功');
    }

    public function delete()
    {
        $id = $this->request()->getQueryParam('id');
        $model = new MenuModel();
        $re = $model->deleteMenu($id);

        if(!$re){
            return $this->response_error('删除菜单失败');
        }

        return $this->response_info('删除菜单成功');
    }

    public function status()
    {
        $id = $this->request()->getQueryParam('id');
        $model = new MenuModel();
        $re = $model->setStatus($id);

        if(!$re){
            return $this->response_error('设置状态失败');
        }

        return $this->response_info('设置状态成功');
    }

    protected function menuValidate($params)
    {
        switch ($params['type']){
            case 1:
                if(!Helper::array_get($params,'route_path')){
                    return '路由地址必填';
                }

                if(!Helper::array_get($params,'icon')){
                    return '图标必填';
                }


                break;
            case 2:

                if(!Helper::array_get($params,'route_path')){
                    return '路由地址必填';
                }

                if(!Helper::array_get($params,'permission')){
                    return '权限标识必填';
                }

                if(!Helper::array_get($params,'component')){
                    return '组件路径必填';
                }

                if(!Helper::array_get($params,'icon')){
                    return '图标必填';
                }

                break;

        }

        return true;
    }

    protected function paramsFilter($params)
    {
        switch ($params['type']){
            case 1:
                unset($params['component']);
                unset($params['permission']);

                break;
            case 3:

                unset($params['icon']);
                unset($params['route_path']);
                unset($params['component']);
                break;
        }

        return array_filter($params);
    }


}
