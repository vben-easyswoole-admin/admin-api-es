<?php


namespace App\HttpController\Admin;


class Index extends AuthBase
{
    public function index()
    {
        $result = [
            'access' => 2000,
            'access_total' => 120000,
            'turnover' => 20000,
            'turnover_total' => 50000,
            'download' => 8000,
            'download_total' => 12000,
            'Trading' => 5000,
            'Trading_total' => 50000,
        ];

        return $this->response_success($result);
    }

}
