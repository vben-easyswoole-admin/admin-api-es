<?php


namespace App\Model;


use EasySwoole\ORM\AbstractModel;

class BaseModel extends AbstractModel
{
    protected $limit = 10;

    protected function pageFormat($list,int $page,int $limit,int $total):array
    {
        return  [
            'list' => $list,
            'page' => $page,
            'limit' => $limit,
            'total' => $total
        ];

    }

}
